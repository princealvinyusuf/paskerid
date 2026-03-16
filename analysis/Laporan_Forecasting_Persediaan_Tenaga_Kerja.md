# Laporan Teknis (Format Tesis)
## Perancangan dan Implementasi Dashboard Forecasting Persediaan Tenaga Kerja Berbasis Data `job_admin_prod.jobseeker`

### Penulis
Tim Pengembangan PaskerID (dibantu AI engineering assistant)

### Tanggal
16 Maret 2026

---

## Abstrak
Perencanaan pasar kerja membutuhkan proyeksi persediaan tenaga kerja yang cepat, dapat dijelaskan, dan dapat dioperasionalkan di sistem produksi. Studi ini merancang dan mengimplementasikan modul **Dashboard Forecasting Persediaan Tenaga Kerja** pada aplikasi PaskerID dengan sumber data utama tabel `job_admin_prod.jobseeker`. Data historis diolah menjadi deret waktu bulanan menggunakan variabel tanggal registrasi (`tanggal_daftar`), kemudian diproyeksikan untuk 6 bulan ke depan menggunakan pendekatan hibrida statistik-pragmatis yang menggabungkan pola musiman, baseline terbaru, dan tren pertumbuhan.

Implementasi dilakukan end-to-end pada level aplikasi Laravel: konfigurasi koneksi database eksternal, pengamanan akses menu forecasting melalui passcode, agregasi data deret waktu, proses forecasting, caching hasil, serta visualisasi dashboard (KPI cards, grafik historis vs forecast, dan tabel proyeksi). Hasil profiling data menunjukkan cakupan data yang luas (jutaan baris, multi-tahun), sehingga layak untuk peramalan volume. Modul yang dihasilkan telah berjalan pada route `/dashboard-distribution/forecasting` dan dapat dijadikan fondasi untuk peningkatan ke model probabilistik/ML lanjutan.

**Kata kunci:** forecasting, persediaan tenaga kerja, time series, Laravel, dashboard, jobseeker.

---

## 1. Pendahuluan

### 1.1 Latar Belakang
Persediaan tenaga kerja (supply side) adalah komponen utama dalam ekosistem pasar kerja. Ketika tren supply tidak dipantau dan diproyeksikan dengan baik, kebijakan intervensi, penempatan kerja, dan program peningkatan kompetensi menjadi reaktif. Oleh karena itu, diperlukan dashboard forecasting yang:
- menggunakan data operasional nyata,
- menghasilkan proyeksi yang cukup akurat untuk keputusan jangka pendek-menengah,
- dapat dijalankan langsung di aplikasi produksi tanpa pipeline kompleks.

### 1.2 Rumusan Masalah
1. Bagaimana menghubungkan aplikasi PaskerID ke database eksternal `job_admin_prod` secara aman dan stabil?
2. Bagaimana membangun metode forecasting praktis dari tabel `jobseeker` yang siap operasional?
3. Bagaimana menyajikan hasil forecasting dalam dashboard yang mudah dibaca untuk pengambil keputusan?

### 1.3 Tujuan
1. Membangun modul forecasting terintegrasi pada menu `/dashboard-distribution`.
2. Menghasilkan prediksi persediaan tenaga kerja 6 bulan ke depan berbasis data historis bulanan.
3. Menyediakan dokumentasi metodologi yang dapat diaudit dan dikembangkan.

### 1.4 Ruang Lingkup
- Data utama: tabel `jobseeker` pada database `job_admin_prod`.
- Target peramalan: **jumlah pendaftar jobseeker per bulan**.
- Horizon forecast: 6 bulan.
- Sistem: Laravel (controller + view + route + cache).
- Akses dashboard forecasting dilindungi passcode.

---

## 2. Tinjauan Singkat Metodologi Forecasting

Untuk kebutuhan implementasi cepat di sistem produksi, digunakan pendekatan deret waktu univariat berbasis volume bulanan:
1. **Agregasi bulanan** dari `tanggal_daftar`.
2. **Estimasi musiman bulanan** (rata-rata per bulan kalender: Jan, Feb, dst).
3. **Estimasi tren pertumbuhan** dari perbandingan 6 bulan dan 12 bulan terakhir.
4. **Forecast rekursif horizon 6 bulan**.

Pendekatan ini dipilih karena:
- transparan (mudah dijelaskan ke pemangku kepentingan),
- robust untuk data volume besar,
- tidak membutuhkan training pipeline ML terpisah.

---

## 3. Data dan Profiling

### 3.1 Sumber Data
- Host: `194.233.86.160`
- Database: `job_admin_prod`
- Tabel: `jobseeker`
- Kolom kunci waktu: `tanggal_daftar` (tipe `date`)

### 3.2 Ringkasan Struktur Data
- Jumlah baris: ±5.25 juta.
- Jumlah kolom: 49.
- Cakupan tanggal `tanggal_daftar`: 2018-03 s.d. 2026-01 (historis bulanan panjang).

### 3.3 Temuan Kualitas Data (Ringkas)
- Data historis cukup panjang dan padat untuk forecasting volume.
- Terdapat indikasi batch ingestion pada `inputted_date` (normal untuk sinkronisasi).
- Ada beberapa anomali teks pada kolom kategorikal tertentu, namun tidak mengganggu target univariat jumlah bulanan.

---

## 4. Metodologi yang Diimplementasikan

### 4.1 Definisi Target
Target model:
\[
Y_t = \text{jumlah jobseeker terdaftar pada bulan } t
\]

Data diperoleh dengan query agregasi:
- `DATE_FORMAT(tanggal_daftar, '%Y-%m-01')` sebagai indeks bulan
- `COUNT(*)` sebagai volume bulanan.

### 4.2 Langkah Perhitungan
Misalkan deret historis bulanan \( Y_1, Y_2, ..., Y_T \).

1. **Rata-rata global**
\[
\bar{Y} = \frac{1}{T}\sum_{t=1}^{T}Y_t
\]

2. **Rata-rata musiman per bulan kalender**  
Untuk setiap bulan \( m \in \{1,\dots,12\} \), hitung:
\[
S_m = \text{rata-rata } Y_t \text{ pada semua periode dengan bulan kalender } m
\]

3. **Pertumbuhan 6 bulanan dan 12 bulanan**
\[
g_6 = \frac{\sum Y_{T-5:T} - \sum Y_{T-11:T-6}}{\sum Y_{T-11:T-6}}
\]
\[
g_{12} = \frac{\sum Y_{T-11:T} - \sum Y_{T-23:T-12}}{\sum Y_{T-23:T-12}}
\]

4. **Gabungan pertumbuhan tahunan**
\[
g_{annual} = 0.4 \cdot g_6 + 0.6 \cdot g_{12}
\]
dengan pembatas (clamp) agar stabil:
\[
g_{annual} \in [-0.4, 0.6]
\]

5. **Konversi ke pertumbuhan bulanan**
\[
g_{month} = (1 + g_{annual})^{1/12} - 1
\]

6. **Baseline terbaru**
\[
B = \text{rata-rata 3 bulan terakhir}
\]

7. **Forecast bulan ke-}i\text{ (}i=1..6\text{)}**
Untuk bulan target dengan kalender \( m_i \):
\[
Base_i = 0.65 \cdot S_{m_i} + 0.35 \cdot B
\]
\[
\hat{Y}_{T+i} = \max\{1,\ \text{round}(Base_i \cdot (1+g_{month})^i)\}
\]

### 4.3 Alasan Pemilihan Formula
- Bobot **0.65 musiman** menekankan pola kalender (umum pada registrasi pasar kerja).
- Bobot **0.35 baseline terbaru** menjaga respons terhadap dinamika terkini.
- Pertumbuhan blended 6/12 bulan mengurangi noise jangka pendek.
- Clamp mencegah lonjakan forecast berlebihan.

---

## 5. Arsitektur Implementasi Sistem

### 5.1 Komponen Backend
File utama:
- `app/Http/Controllers/DashboardDistributionController.php`
- `config/database.php`
- `routes/web.php`

Fungsi inti:
- validasi passcode forecasting,
- otorisasi session menu forecasting,
- query agregasi historis,
- kalkulasi forecast,
- cache hasil payload forecasting (`30 menit`).

### 5.2 Koneksi Database Eksternal
Ditambahkan koneksi khusus:
- nama koneksi: `job_admin_prod`
- digunakan via `DB::connection('job_admin_prod')`.

Untuk ketahanan runtime:
- fallback konfigurasi koneksi dinamis disiapkan jika cache konfigurasi belum sinkron,
- rekoneksi dipaksa (`purge/reconnect`) sebelum query forecasting.

### 5.3 Komponen Frontend Dashboard
View:
- `resources/views/dashboard_distribution/forecasting.blade.php`

Elemen dashboard:
1. KPI Cards:
   - jumlah bulan historis,
   - bulan aktual terakhir,
   - rata-rata 6 bulan terakhir,
   - rata-rata forecast 6 bulan.
2. Grafik historis vs forecast (Chart.js).
3. Tabel proyeksi 6 bulan ke depan.

### 5.4 Pengamanan Akses
Route forecasting dilindungi:
- passcode gate (default `01062025`),
- session flag akses forecasting.

---

## 6. Hasil Implementasi

### 6.1 Status Fungsional
Fitur berhasil diimplementasikan:
- menu **Forecasting** muncul di halaman `/dashboard-distribution`,
- popup passcode aktif,
- akses ke halaman forecasting berhasil setelah verifikasi passcode,
- data diambil dari `job_admin_prod.jobseeker`,
- dashboard menampilkan KPI + grafik + tabel proyeksi.

### 6.2 Validasi Teknis
- Koneksi PHP langsung ke DB eksternal berhasil.
- Error type mismatch pada tahap pengembangan telah diselesaikan:
  - Collection vs array di pipeline forecasting.
- Handler error kini menampilkan detail saat mode debug aktif.

---

## 7. Pembahasan

### 7.1 Kekuatan Pendekatan Saat Ini
1. **Operasional cepat**: tidak memerlukan infra training ML terpisah.
2. **Explainable**: formula dapat dijelaskan ke user non-teknis.
3. **Skalabel untuk volume besar**: query berbasis agregasi bulanan.
4. **Cocok untuk dashboard kebijakan jangka pendek** (rolling 6 bulan).

### 7.2 Keterbatasan
1. Model belum menyediakan **interval ketidakpastian** (confidence interval).
2. Belum memakai variabel eksogen (kampanye, policy shock, season event nasional).
3. Belum ada backtesting metrik formal berjalan otomatis (MAPE/RMSE rolling).
4. Fokus saat ini masih univariat nasional, belum segmentasi provinsi/kota.

---

## 8. Rencana Pengembangan (Roadmap Ilmiah)

### Tahap 1 (Short Term)
- Tambahkan metrik evaluasi rolling:
  - MAE, RMSE, MAPE pada historical walk-forward.
- Tambahkan confidence band:
  - skenario low/base/high.

### Tahap 2 (Medium Term)
- Segmentasi forecast:
  - per provinsi,
  - per jenis kelamin,
  - per kelompok umur.
- Monitoring kualitas data otomatis:
  - missing spike,
  - anomaly detection pada nilai bulanan.

### Tahap 3 (Advanced)
- Bandingkan model:
  - SARIMA,
  - Prophet,
  - XGBoost with lag features.
- Pilih model terbaik berbasis backtesting multi-horizon.
- Implement model registry ringan untuk deployment terkontrol.

---

## 9. Kesimpulan
Penelitian terapan ini berhasil membangun dan menerapkan modul **Dashboard Forecasting Persediaan Tenaga Kerja** berbasis data operasional `job_admin_prod.jobseeker` pada aplikasi PaskerID. Metodologi yang diimplementasikan menggabungkan pola musiman, baseline terbaru, dan tren pertumbuhan untuk menghasilkan proyeksi 6 bulan yang transparan serta siap digunakan dalam proses pengambilan keputusan.  

Walaupun pendekatan saat ini masih bersifat point forecast tanpa interval ketidakpastian, sistem telah memenuhi tujuan utama implementasi produksi: terkoneksi ke data riil, aman diakses, cepat dijalankan, mudah dijelaskan, dan dapat dikembangkan ke model yang lebih canggih.

---

## Lampiran A — Rute dan Modul Terkait
- `GET /dashboard-distribution`
- `POST /dashboard-distribution/forecasting/verify-passcode`
- `GET /dashboard-distribution/forecasting`

## Lampiran B — Variabel Konfigurasi Utama
- `FORECASTING_MENU_PASSCODE`
- `JOB_ADMIN_PROD_DB_HOST`
- `JOB_ADMIN_PROD_DB_PORT`
- `JOB_ADMIN_PROD_DB_DATABASE`
- `JOB_ADMIN_PROD_DB_USERNAME`
- `JOB_ADMIN_PROD_DB_PASSWORD`

## Lampiran C — Catatan Reproducibility
Untuk memastikan perubahan konfigurasi terbaca oleh aplikasi:
```bash
php artisan optimize:clear
```

