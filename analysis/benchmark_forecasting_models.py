from __future__ import annotations

import json
import math
from dataclasses import dataclass
from typing import Callable, Dict, List, Tuple

import numpy as np
import pandas as pd
import pymysql
from sklearn.ensemble import GradientBoostingRegressor, RandomForestRegressor
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_absolute_error, mean_squared_error
from statsmodels.tsa.holtwinters import ExponentialSmoothing
from statsmodels.tsa.statespace.sarimax import SARIMAX


DB_CONFIG = {
    "host": "194.233.86.160",
    "user": "pasker",
    "password": "Getjoblivebetter!",
    "database": "job_admin_prod",
    "charset": "utf8mb4",
}


def fetch_monthly_series() -> pd.Series:
    conn = pymysql.connect(**DB_CONFIG, cursorclass=pymysql.cursors.DictCursor)
    try:
        with conn.cursor() as cur:
            cur.execute(
                """
                SELECT DATE_FORMAT(tanggal_daftar, '%Y-%m-01') AS ym, COUNT(*) AS cnt
                FROM jobseeker
                WHERE tanggal_daftar IS NOT NULL
                GROUP BY ym
                ORDER BY ym
                """
            )
            rows = cur.fetchall()
    finally:
        conn.close()

    df = pd.DataFrame(rows)
    df["ym"] = pd.to_datetime(df["ym"])
    df["cnt"] = df["cnt"].astype(int)
    s = pd.Series(df["cnt"].values, index=df["ym"])
    s.index.name = "month"
    s.name = "jobseeker_count"
    return s


def production_blend_forecast(train: pd.Series, horizon: int) -> np.ndarray:
    values = train.values.astype(float)
    global_avg = max(1.0, values.mean())

    by_month: Dict[int, List[float]] = {}
    for dt, val in train.items():
        m = int(dt.month)
        by_month.setdefault(m, []).append(float(val))

    def safe_sum(arr: np.ndarray) -> float:
        return float(np.sum(arr)) if arr.size else 0.0

    last6 = values[-6:]
    prev6 = values[-12:-6]
    last12 = values[-12:]
    prev12 = values[-24:-12]

    g6 = ((safe_sum(last6) - safe_sum(prev6)) / safe_sum(prev6)) if safe_sum(prev6) > 0 else 0.0
    g12 = ((safe_sum(last12) - safe_sum(prev12)) / safe_sum(prev12)) if safe_sum(prev12) > 0 else 0.0
    annual_growth = max(-0.4, min(0.6, (0.4 * g6) + (0.6 * g12)))
    monthly_growth = (1.0 + annual_growth) ** (1.0 / 12.0) - 1.0

    last3 = values[-3:] if len(values) >= 3 else values
    recent_baseline = float(np.mean(last3)) if len(last3) > 0 else global_avg
    max_date = train.index.max()

    fc = []
    for i in range(1, horizon + 1):
        target_date = (max_date + pd.offsets.MonthBegin(i)).to_pydatetime()
        m = target_date.month
        same_month_avg = float(np.mean(by_month[m])) if m in by_month else global_avg
        base = (0.65 * same_month_avg) + (0.35 * recent_baseline)
        trend_multiplier = (1.0 + monthly_growth) ** i
        fc.append(max(1.0, round(base * trend_multiplier)))
    return np.array(fc, dtype=float)


def seasonal_naive_forecast(train: pd.Series, horizon: int) -> np.ndarray:
    vals = train.values.astype(float)
    if len(vals) < 12:
        return np.repeat(vals[-1], horizon)
    out = []
    for i in range(1, horizon + 1):
        out.append(vals[-12 + ((i - 1) % 12)])
    return np.array(out, dtype=float)


def holt_winters_forecast(train: pd.Series, horizon: int) -> np.ndarray:
    vals = train.values.astype(float)
    if len(vals) < 24:
        return seasonal_naive_forecast(train, horizon)
    model = ExponentialSmoothing(
        vals, trend="add", seasonal="add", seasonal_periods=12, initialization_method="estimated"
    )
    fit = model.fit(optimized=True)
    fc = fit.forecast(horizon)
    return np.array(fc, dtype=float)


def select_sarimax_order(series: pd.Series) -> Tuple[Tuple[int, int, int], Tuple[int, int, int, int]]:
    candidate_orders = [(1, 1, 1), (2, 1, 1), (1, 1, 2), (2, 1, 2), (0, 1, 1)]
    candidate_seasonal = [(1, 1, 1, 12), (0, 1, 1, 12), (1, 0, 1, 12)]

    best = None
    y = series.values.astype(float)
    for order in candidate_orders:
        for seas in candidate_seasonal:
            try:
                fit = SARIMAX(
                    y,
                    order=order,
                    seasonal_order=seas,
                    enforce_stationarity=False,
                    enforce_invertibility=False,
                ).fit(disp=False)
                aic = fit.aic
                if not np.isfinite(aic):
                    continue
                if best is None or aic < best[0]:
                    best = (aic, order, seas)
            except Exception:
                continue
    if best is None:
        return (1, 1, 1), (1, 1, 1, 12)
    return best[1], best[2]


def sarimax_forecast(train: pd.Series, horizon: int, order: Tuple[int, int, int], seas: Tuple[int, int, int, int]) -> np.ndarray:
    y = train.values.astype(float)
    fit = SARIMAX(
        y,
        order=order,
        seasonal_order=seas,
        enforce_stationarity=False,
        enforce_invertibility=False,
    ).fit(disp=False)
    fc = fit.forecast(horizon)
    return np.array(fc, dtype=float)


def make_ml_features(series: pd.Series) -> pd.DataFrame:
    df = pd.DataFrame({"y": series.values.astype(float)}, index=series.index)
    for lag in [1, 2, 3, 6, 12]:
        df[f"lag_{lag}"] = df["y"].shift(lag)
    m = df.index.month
    df["month_sin"] = np.sin(2 * np.pi * m / 12.0)
    df["month_cos"] = np.cos(2 * np.pi * m / 12.0)
    return df.dropna()


def ml_forecast(train: pd.Series, horizon: int, model_name: str) -> np.ndarray:
    feat = make_ml_features(train)
    if len(feat) < 24:
        return seasonal_naive_forecast(train, horizon)

    X = feat.drop(columns=["y"]).values
    y = feat["y"].values
    if model_name == "linear_lag":
        model = LinearRegression()
    elif model_name == "rf_lag":
        model = RandomForestRegressor(n_estimators=300, random_state=42)
    elif model_name == "gbr_lag":
        model = GradientBoostingRegressor(random_state=42)
    else:
        raise ValueError(model_name)
    model.fit(X, y)

    history = train.copy()
    out = []
    for _ in range(horizon):
        idx = history.index.max() + pd.offsets.MonthBegin(1)
        row = {}
        for lag in [1, 2, 3, 6, 12]:
            row[f"lag_{lag}"] = float(history.iloc[-lag]) if len(history) >= lag else float(history.iloc[-1])
        row["month_sin"] = math.sin(2 * math.pi * idx.month / 12.0)
        row["month_cos"] = math.cos(2 * math.pi * idx.month / 12.0)
        pred = float(model.predict(pd.DataFrame([row]).values)[0])
        pred = max(1.0, pred)
        out.append(pred)
        history.loc[idx] = pred
    return np.array(out, dtype=float)


@dataclass
class ModelResult:
    model: str
    horizon: int
    mae: float
    rmse: float
    mape: float
    folds: int


def evaluate_model(
    series: pd.Series,
    model_name: str,
    horizon: int,
    min_train: int,
    sarimax_conf: Tuple[Tuple[int, int, int], Tuple[int, int, int, int]] | None = None,
) -> ModelResult:
    actual_all, pred_all = [], []
    n = len(series)

    for end in range(min_train, n - horizon + 1):
        train = series.iloc[:end]
        actual = series.iloc[end : end + horizon].values.astype(float)

        try:
            if model_name == "production_blend":
                pred = production_blend_forecast(train, horizon)
            elif model_name == "seasonal_naive":
                pred = seasonal_naive_forecast(train, horizon)
            elif model_name == "holt_winters":
                pred = holt_winters_forecast(train, horizon)
            elif model_name == "sarimax":
                assert sarimax_conf is not None
                pred = sarimax_forecast(train, horizon, sarimax_conf[0], sarimax_conf[1])
            elif model_name in {"linear_lag", "rf_lag", "gbr_lag"}:
                pred = ml_forecast(train, horizon, model_name)
            else:
                continue
        except Exception:
            continue

        actual_all.extend(actual.tolist())
        pred_all.extend(pred.tolist())

    actual_arr = np.array(actual_all, dtype=float)
    pred_arr = np.array(pred_all, dtype=float)
    mae = mean_absolute_error(actual_arr, pred_arr)
    rmse = math.sqrt(mean_squared_error(actual_arr, pred_arr))
    mape = np.mean(np.abs((actual_arr - pred_arr) / np.clip(actual_arr, 1e-9, None))) * 100
    folds = len(actual_all) // horizon if horizon > 0 else 0
    return ModelResult(model=model_name, horizon=horizon, mae=mae, rmse=rmse, mape=mape, folds=folds)


def main() -> None:
    series = fetch_monthly_series()
    min_train = 36
    sarimax_conf = select_sarimax_order(series.iloc[:-6])

    models = ["production_blend", "seasonal_naive", "holt_winters", "sarimax", "linear_lag", "rf_lag", "gbr_lag"]
    results: List[ModelResult] = []
    for h in [1, 3, 6]:
        for m in models:
            r = evaluate_model(series, m, horizon=h, min_train=min_train, sarimax_conf=sarimax_conf)
            results.append(r)

    rows = [
        {
            "model": r.model,
            "horizon": r.horizon,
            "mae": round(r.mae, 4),
            "rmse": round(r.rmse, 4),
            "mape": round(r.mape, 4),
            "folds": r.folds,
        }
        for r in results
    ]
    out_df = pd.DataFrame(rows).sort_values(["horizon", "mape", "rmse", "mae"]).reset_index(drop=True)
    out_df.to_csv("analysis/forecast_model_benchmark.csv", index=False)

    best_by_h = (
        out_df.sort_values(["horizon", "mape", "rmse", "mae"])
        .groupby("horizon", as_index=False)
        .first()
        .to_dict(orient="records")
    )
    summary = {
        "series_points": int(len(series)),
        "series_start": str(series.index.min().date()),
        "series_end": str(series.index.max().date()),
        "sarimax_order": sarimax_conf[0],
        "sarimax_seasonal_order": sarimax_conf[1],
        "best_by_horizon": best_by_h,
    }
    with open("analysis/forecast_model_benchmark_summary.json", "w") as f:
        json.dump(summary, f, indent=2)

    print("Wrote analysis/forecast_model_benchmark.csv")
    print("Wrote analysis/forecast_model_benchmark_summary.json")
    print(json.dumps(summary, indent=2))


if __name__ == "__main__":
    main()

