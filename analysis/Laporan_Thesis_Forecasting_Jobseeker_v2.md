# LAPORAN THESIS TEKNIS
## Pemilihan Model Forecasting Paling Akurat untuk Persediaan Tenaga Kerja pada Data `job_admin_prod.jobseeker`

**Proyek:** Dashboard Forecasting Persediaan Tenaga Kerja PaskerID  
**Lokasi Implementasi:** `/dashboard-distribution/forecasting`  
**Tanggal:** 16 Maret 2026

---

## Abstrak
Penelitian ini bertujuan menentukan model forecasting paling akurat untuk memproyeksikan persediaan tenaga kerja berdasarkan data registrasi jobseeker pada tabel `job_admin_prod.jobseeker`. Data deret waktu dibentuk pada level bulanan dari `tanggal_daftar` dengan cakupan Maret 2018 hingga Januari 2026 (94 titik waktu). Berbeda dari pendekatan satu model, studi ini melakukan komparasi multi-model menggunakan rolling backtest expanding window pada horizon 1, 3, dan 6 bulan.

Model yang diuji: `production_blend` (model produksi saat ini), `seasonal_naive`, `holt_winters`, `sarimax`, `linear_lag`, `rf_lag`, dan `gbr_lag`. Evaluasi memakai MAE, RMSE, dan MAPE. Hasil menunjukkan **model terbaik bergantung pada horizon**: `gbr_lag` terbaik untuk horizon 1 bulan (MAPE 38.99%), sedangkan `production_blend` terbaik untuk horizon 3 dan 6 bulan (MAPE 50.65% dan 51.26%). Karena dashboard saat ini digunakan untuk proyeksi 6 bulan, model produksi yang sekarang dipakai (`production_blend`) tetap merupakan pilihan paling tepat untuk tujuan operasional saat ini.

Kontribusi utama penelitian ini adalah: (1) validasi empiris pemilihan model berbasis backtest nyata, (2) justifikasi ilmiah atas model yang dipakai di production, dan (3) roadmap pengembangan ke model lanjutan berbasis interval ketidakpastian dan fitur eksogen.

**Kata kunci:** forecasting, jobseeker, time series, rolling backtest, model selection, tenaga kerja.

---

## BAB 1. Pendahuluan

### 1.1 Latar Belakang
Perencanaan pasar kerja membutuhkan prediksi supply tenaga kerja yang reliabel. Tanpa model yang divalidasi, dashboard hanya menjadi visualisasi historis dan berisiko menghasilkan keputusan yang bias. Oleh sebab itu diperlukan proses ilmiah untuk:
1. membangun kandidat model,
2. menguji akurasi out-of-sample,
3. memilih model terbaik berdasarkan horizon bisnis.

### 1.2 Rumusan Masalah
1. Model forecasting apa yang paling akurat untuk data `jobseeker`?
2. Apakah model yang sudah diimplementasikan di dashboard saat ini sudah tepat?
3. Bagaimana metodologi evaluasi model yang dapat dipertanggungjawabkan secara akademik?

### 1.3 Tujuan Penelitian
1. Menyusun benchmark forecasting multi-model pada data aktual.
2. Menentukan model terbaik untuk horizon 1, 3, dan 6 bulan.
3. Menyusun rekomendasi model final untuk sistem produksi dashboard.

### 1.4 Batasan
- Data target: agregasi bulanan `COUNT(*)` jobseeker berdasarkan `tanggal_daftar`.
- Periode data: 2018-03 s.d. 2026-01.
- Evaluasi fokus pada akurasi prediksi volume, bukan inferensi kausal ekonomi.

---

## BAB 2. Data dan Pra-pemrosesan

### 2.1 Sumber Data
- **Database:** `job_admin_prod`
- **Tabel:** `jobseeker`
- **Kolom waktu:** `tanggal_daftar`
- **Ukuran tabel mentah:** ~5.25 juta baris

### 2.2 Pembentukan Deret Waktu
Data dibentuk menjadi deret bulanan:
- Indeks waktu: `YYYY-MM-01`
- Nilai: jumlah registrasi jobseeker per bulan
- Jumlah titik observasi: **94 bulan**

### 2.3 Karakteristik Data
- Terdapat fluktuasi tinggi antar bulan.
- Ada periode lonjakan (spike) dan penurunan tajam.
- Pola musiman kalender tampak, namun tidak selalu stabil antar tahun.

Implikasinya: model yang terlalu linear cenderung underfit, sedangkan model terlalu kompleks berisiko overfit jika tidak diuji rolling.

---

## BAB 3. Metodologi Eksperimen

### 3.1 Desain Evaluasi
Digunakan **expanding window rolling backtest**:
- Minimal data latih awal: 36 bulan.
- Pada setiap fold, model dilatih pada data historis sampai titik tertentu, lalu memprediksi ke depan sesuai horizon.
- Horizon diuji: 1, 3, 6 bulan.

Skema ini meniru kondisi operasional nyata (forecast ke masa depan tanpa melihat data masa depan).

### 3.2 Model yang Dibandingkan
1. `production_blend` (model produksi saat ini)
2. `seasonal_naive`
3. `holt_winters` (Exponential Smoothing musiman)
4. `sarimax` (order terbaik hasil seleksi AIC)
5. `linear_lag` (Linear Regression berbasis lag)
6. `rf_lag` (Random Forest berbasis lag)
7. `gbr_lag` (Gradient Boosting berbasis lag)

### 3.3 Fitur untuk Model Lag-based
- lag: 1, 2, 3, 6, 12 bulan
- fitur musiman: `month_sin`, `month_cos`

### 3.4 Metrik Evaluasi
\[
MAE = \frac{1}{n}\sum_{i=1}^{n} |y_i - \hat{y}_i|
\]
\[
RMSE = \sqrt{\frac{1}{n}\sum_{i=1}^{n}(y_i - \hat{y}_i)^2}
\]
\[
MAPE = \frac{100\%}{n}\sum_{i=1}^{n}\left|\frac{y_i - \hat{y}_i}{y_i}\right|
\]

Catatan: MAPE pada data ini relatif tinggi karena ada fase historis dengan volume sangat kecil di awal seri, sehingga error relatif membesar.

### 3.5 Konfigurasi SARIMAX
Hasil seleksi parameter:
- order: **(1,1,2)**
- seasonal order: **(0,1,1,12)**

---

## BAB 4. Hasil Benchmark

Sumber hasil: `analysis/forecast_model_benchmark.csv`  
Ringkasan otomatis: `analysis/forecast_model_benchmark_summary.json`

### 4.1 Horizon 1 Bulan
| Peringkat | Model | MAE | RMSE | MAPE |
|---|---|---:|---:|---:|
| 1 | **gbr_lag** | 30,643.14 | 60,506.36 | **38.99%** |
| 2 | rf_lag | 31,383.69 | 62,559.86 | 45.95% |
| 3 | production_blend | 33,102.19 | 64,749.59 | 46.81% |

**Kesimpulan horizon 1:** `gbr_lag` paling akurat.

### 4.2 Horizon 3 Bulan
| Peringkat (MAPE) | Model | MAE | RMSE | MAPE |
|---|---|---:|---:|---:|
| 1 | **production_blend** | 36,458.65 | **68,480.94** | **50.65%** |
| 2 | gbr_lag | 35,194.13 | 68,887.68 | 53.54% |
| 3 | rf_lag | **34,953.12** | 70,920.07 | 59.37% |

**Kesimpulan horizon 3:** berdasarkan MAPE dan RMSE, `production_blend` terbaik.

### 4.3 Horizon 6 Bulan
| Peringkat (MAPE) | Model | MAE | RMSE | MAPE |
|---|---|---:|---:|---:|
| 1 | **production_blend** | 37,396.70 | **70,824.11** | **51.26%** |
| 2 | rf_lag | 38,295.10 | 76,195.15 | 69.81% |
| 3 | gbr_lag | 42,165.52 | 80,072.70 | 70.37% |

**Kesimpulan horizon 6:** `production_blend` paling konsisten terbaik untuk penggunaan dashboard saat ini.

### 4.4 Ringkasan “Model Terbaik per Horizon”
- Horizon 1 bulan: **gbr_lag**
- Horizon 3 bulan: **production_blend**
- Horizon 6 bulan: **production_blend**

---

## BAB 5. Pembahasan

### 5.1 Apakah model sekarang sudah terbaik?
**Jawaban: terbaik untuk kebutuhan dashboard 3–6 bulan, namun bukan terbaik universal.**

Karena dashboard saat ini fokus proyeksi 6 bulan, model `production_blend` tepat dipertahankan. Jika use-case berubah ke prediksi sangat pendek (1 bulan), `gbr_lag` lebih unggul.

### 5.2 Kenapa SARIMAX dan Holt-Winters kalah?
Kemungkinan penyebab:
1. Data menunjukkan perubahan pola struktural yang tidak selalu ditangkap baik model parametrik klasik.
2. Spike event-driven membuat model musiman murni kurang adaptif.
3. Nilai awal yang kecil memperberat error relatif (MAPE), terutama model yang cenderung smoothing.

### 5.3 Implikasi Operasional
1. Pertahankan `production_blend` untuk forecast 6 bulan di dashboard.
2. Tambahkan mode “short-term forecast” (1 bulan) menggunakan `gbr_lag`.
3. Tampilkan label horizon di UI agar interpretasi hasil tidak keliru.

---

## BAB 6. Metodologi Model Produksi (`production_blend`)

### 6.1 Komponen
1. Musiman bulanan (rata-rata per bulan kalender)
2. Baseline terbaru (rata-rata 3 bulan terakhir)
3. Tren pertumbuhan (gabungan pertumbuhan 6 dan 12 bulan)

### 6.2 Formula Inti
\[
g_{annual}= clamp(0.4\cdot g_6 + 0.6\cdot g_{12}, -0.4, 0.6)
\]
\[
g_{month}=(1+g_{annual})^{1/12}-1
\]
\[
Base_i = 0.65\cdot SeasonalAvg_{m_i}+0.35\cdot RecentBaseline
\]
\[
\hat{Y}_{t+i}=round(Base_i\cdot (1+g_{month})^i)
\]

### 6.3 Alasan Pemilihan Bobot
- Bobot musiman lebih besar untuk menjaga pola kalender.
- Baseline terbaru menjaga sensitivitas pada perubahan terbaru.
- Clamp tren mencegah forecast over-reactive.

---

## BAB 7. Implementasi Sistem

### 7.1 Integrasi Aplikasi
- Route forecasting ditambahkan dan dilindungi passcode.
- Koneksi database eksternal `job_admin_prod` dipakai untuk query `jobseeker`.
- Payload forecasting dicache 30 menit untuk efisiensi.
- Halaman dashboard menampilkan KPI, chart historis-vs-forecast, dan tabel proyeksi.

### 7.2 Validasi Teknis yang Telah Dilakukan
1. Uji konektivitas langsung ke DB eksternal berhasil.
2. Debug error tipe data (Collection vs array) telah diselesaikan.
3. Modul sudah berjalan pada endpoint produksi.

---

## BAB 8. Keterbatasan Penelitian
1. Evaluasi belum memasukkan variabel eksogen (event/kampanye/kebijakan).
2. Belum ada prediction interval (hanya point forecast).
3. MAPE sensitif terhadap volume kecil pada awal seri.
4. Studi fokus univariat agregat nasional.

---

## BAB 9. Rekomendasi Pengembangan Lanjutan

### 9.1 Jangka Pendek
1. Dual-model serving:
   - 1 bulan: `gbr_lag`
   - 3/6 bulan: `production_blend`
2. Tambahkan confidence band (P10/P50/P90) via bootstrap residual.
3. Logging evaluasi bulanan otomatis.

### 9.2 Jangka Menengah
1. Forecast per provinsi.
2. Forecast per segmen usia/jenis kelamin/pendidikan.
3. Drift detection bulanan.

### 9.3 Jangka Panjang
1. Probabilistic forecasting.
2. Ensemble stacking antar model.
3. Auto-retraining pipeline dengan model registry.

---

## BAB 10. Kesimpulan
Berdasarkan benchmark kuantitatif pada data aktual `job_admin_prod.jobseeker`, tidak ada satu model yang selalu terbaik untuk semua horizon. `gbr_lag` unggul untuk horizon 1 bulan, sedangkan `production_blend` unggul untuk horizon 3 dan 6 bulan. Karena dashboard operasional saat ini ditujukan untuk horizon 6 bulan, maka model yang sedang berjalan (`production_blend`) telah terbukti sebagai pilihan paling akurat dan paling sesuai secara operasional.

Dengan demikian, implementasi saat ini sudah valid secara teknis dan metodologis untuk use-case dashboard yang ada. Pengembangan berikutnya direkomendasikan ke arsitektur multi-model berdasarkan horizon dan penambahan interval ketidakpastian.

---

## Lampiran A — Artefak Eksperimen
- Script benchmark: `analysis/benchmark_forecasting_models.py`
- Hasil detail: `analysis/forecast_model_benchmark.csv`
- Ringkasan hasil: `analysis/forecast_model_benchmark_summary.json`

## Lampiran B — Reproducibility
Jalankan ulang benchmark:
```bash
.venv/bin/python analysis/benchmark_forecasting_models.py
```

