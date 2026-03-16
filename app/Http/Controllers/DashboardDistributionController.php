<?php

namespace App\Http\Controllers;

use App\Models\DashboardDistribution;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DashboardDistributionController extends Controller
{
    private const FORECASTING_SESSION_KEY = 'dashboard_distribution_forecasting_access_granted';
    private const FORECASTING_DB_CONNECTION = 'job_admin_prod';

    public function index()
    {
        $dashboard = DashboardDistribution::first();
        return view('dashboard_distribution.index', compact('dashboard'));
    }

    public function verifyForecastingPasscode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'passcode' => 'required|string|max:255',
        ]);

        $expectedPasscode = (string) env('FORECASTING_MENU_PASSCODE', '01062025');
        $submittedPasscode = trim((string) $validated['passcode']);

        if ($submittedPasscode !== '' && hash_equals($expectedPasscode, $submittedPasscode)) {
            $request->session()->put(self::FORECASTING_SESSION_KEY, true);
            $request->session()->regenerate();

            return redirect()->route('dashboard.distribution.forecasting');
        }

        return redirect()
            ->route('dashboard.distribution')
            ->withErrors(['passcode' => 'Passcode tidak valid.'])
            ->withInput();
    }

    public function forecasting(Request $request): View|RedirectResponse
    {
        if (!(bool) $request->session()->get(self::FORECASTING_SESSION_KEY, false)) {
            return redirect()
                ->route('dashboard.distribution')
                ->withErrors(['passcode' => 'Masukkan passcode untuk mengakses menu Forecasting.']);
        }

        $forecastPayload = $this->buildForecastPayload();

        return view('dashboard_distribution.forecasting', [
            'forecastPayload' => $forecastPayload,
        ]);
    }

    private function buildForecastPayload(): array
    {
        try {
            $this->ensureForecastingConnectionConfigured();

            if (!Schema::connection(self::FORECASTING_DB_CONNECTION)->hasTable('jobseeker')) {
                return [
                    'ready' => false,
                    'message' => 'Tabel jobseeker belum tersedia pada koneksi database forecasting.',
                ];
            }

            return Cache::remember('dashboard_distribution_forecasting_payload', now()->addMinutes(30), function () {
                $rows = DB::connection(self::FORECASTING_DB_CONNECTION)
                    ->table('jobseeker')
                    ->selectRaw("DATE_FORMAT(tanggal_daftar, '%Y-%m-01') AS ym, COUNT(*) AS cnt")
                    ->whereNotNull('tanggal_daftar')
                    ->groupBy('ym')
                    ->orderBy('ym')
                    ->get();

                if ($rows->isEmpty()) {
                    return [
                        'ready' => false,
                        'message' => 'Data tanggal_daftar pada tabel jobseeker belum tersedia.',
                    ];
                }

                $history = $rows->map(function ($row) {
                    return [
                        'date' => CarbonImmutable::parse((string) $row->ym)->startOfMonth(),
                        'count' => (int) $row->cnt,
                    ];
                })->values()->all();

                $historyLabels = $history->map(fn ($item) => $item['date']->format('M Y'))->all();
                $historyCounts = $history->map(fn ($item) => $item['count'])->all();

                $maxDate = $history->last()['date'];
                $forecastPoints = $this->generateForecastPoints($history, $maxDate, 6);
                $forecastLabels = array_map(fn ($item) => $item['date']->format('M Y'), $forecastPoints);
                $forecastCounts = array_map(fn ($item) => $item['count'], $forecastPoints);

                $last12 = array_slice($historyCounts, -12);
                $prev12 = array_slice($historyCounts, -24, 12);
                $yoyGrowthPct = (!empty($prev12) && array_sum($prev12) > 0)
                    ? round(((array_sum($last12) - array_sum($prev12)) / array_sum($prev12)) * 100, 2)
                    : null;

                $avgLast6 = !empty(array_slice($historyCounts, -6))
                    ? (int) round(array_sum(array_slice($historyCounts, -6)) / count(array_slice($historyCounts, -6)))
                    : 0;

                $forecastAvg6 = !empty($forecastCounts)
                    ? (int) round(array_sum($forecastCounts) / count($forecastCounts))
                    : 0;

                return [
                    'ready' => true,
                    'meta' => [
                        'history_months' => count($historyCounts),
                        'last_actual_month' => $maxDate->format('F Y'),
                        'avg_last_6_months' => $avgLast6,
                        'forecast_avg_next_6_months' => $forecastAvg6,
                        'yoy_growth_pct' => $yoyGrowthPct,
                    ],
                    'series' => [
                        'history_labels' => $historyLabels,
                        'history_counts' => $historyCounts,
                        'forecast_labels' => $forecastLabels,
                        'forecast_counts' => $forecastCounts,
                    ],
                    'table' => array_map(function ($item) {
                        return [
                            'period' => $item['date']->format('F Y'),
                            'value' => $item['count'],
                        ];
                    }, $forecastPoints),
                    'method' => 'Forecast menggunakan kombinasi seasonal pattern bulanan dan tren pertumbuhan terbaru.',
                ];
            });
        } catch (Throwable $e) {
            Log::error('Forecasting payload load failed', [
                'connection' => self::FORECASTING_DB_CONNECTION,
                'error' => $e->getMessage(),
            ]);

            $message = 'Data forecasting belum dapat dimuat saat ini. Silakan cek koneksi database.';
            if ((bool) config('app.debug', false)) {
                $message .= ' Detail: ' . $e->getMessage();
            }

            return [
                'ready' => false,
                'message' => $message,
            ];
        }
    }

    private function ensureForecastingConnectionConfigured(): void
    {
        $connectionName = self::FORECASTING_DB_CONNECTION;
        $configured = (array) config('database.connections.' . $connectionName, []);

        if (empty($configured) || empty($configured['driver'])) {
            config([
                'database.connections.' . $connectionName => [
                    'driver' => 'mysql',
                    'host' => (string) env('JOB_ADMIN_PROD_DB_HOST', '194.233.86.160'),
                    'port' => (string) env('JOB_ADMIN_PROD_DB_PORT', '3306'),
                    'database' => (string) env('JOB_ADMIN_PROD_DB_DATABASE', 'job_admin_prod'),
                    'username' => (string) env('JOB_ADMIN_PROD_DB_USERNAME', 'pasker'),
                    'password' => (string) env('JOB_ADMIN_PROD_DB_PASSWORD', 'Getjoblivebetter!'),
                    'unix_socket' => (string) env('JOB_ADMIN_PROD_DB_SOCKET', ''),
                    'charset' => (string) env('JOB_ADMIN_PROD_DB_CHARSET', 'utf8mb4'),
                    'collation' => (string) env('JOB_ADMIN_PROD_DB_COLLATION', 'utf8mb4_unicode_ci'),
                    'prefix' => '',
                    'prefix_indexes' => true,
                    'strict' => true,
                    'engine' => null,
                ],
            ]);
        }

        DB::purge($connectionName);
        DB::reconnect($connectionName);
    }

    /**
     * @param array<int, array{date: CarbonImmutable, count: int}> $history
     * @return array<int, array{date: CarbonImmutable, count: int}>
     */
    private function generateForecastPoints(array $history, CarbonImmutable $maxDate, int $horizon): array
    {
        $historyCounts = array_values(array_map(fn ($item) => (int) $item['count'], $history));
        $globalAvg = max(1.0, array_sum($historyCounts) / max(1, count($historyCounts)));

        $byMonth = [];
        foreach ($history as $point) {
            $month = (int) $point['date']->format('n');
            if (!isset($byMonth[$month])) {
                $byMonth[$month] = [];
            }
            $byMonth[$month][] = (int) $point['count'];
        }

        $last6 = array_slice($historyCounts, -6);
        $prev6 = array_slice($historyCounts, -12, 6);
        $last12 = array_slice($historyCounts, -12);
        $prev12 = array_slice($historyCounts, -24, 12);

        $g6 = (!empty($prev6) && array_sum($prev6) > 0)
            ? ((array_sum($last6) - array_sum($prev6)) / array_sum($prev6))
            : 0.0;
        $g12 = (!empty($prev12) && array_sum($prev12) > 0)
            ? ((array_sum($last12) - array_sum($prev12)) / array_sum($prev12))
            : 0.0;

        $annualGrowth = max(-0.4, min(0.6, (0.4 * $g6) + (0.6 * $g12)));
        $monthlyGrowth = pow(1 + $annualGrowth, 1 / 12) - 1;

        $recentBaseline = !empty($last3 = array_slice($historyCounts, -3))
            ? (array_sum($last3) / count($last3))
            : $globalAvg;

        $result = [];
        for ($i = 1; $i <= $horizon; $i++) {
            $targetDate = $maxDate->addMonthsNoOverflow($i)->startOfMonth();
            $month = (int) $targetDate->format('n');
            $sameMonthAvg = isset($byMonth[$month]) && count($byMonth[$month]) > 0
                ? (array_sum($byMonth[$month]) / count($byMonth[$month]))
                : $globalAvg;

            $base = (0.65 * $sameMonthAvg) + (0.35 * $recentBaseline);
            $trendMultiplier = pow(1 + $monthlyGrowth, $i);
            $forecastValue = (int) round(max(1, $base * $trendMultiplier));

            $result[] = [
                'date' => $targetDate,
                'count' => $forecastValue,
            ];
        }

        return $result;
    }
}