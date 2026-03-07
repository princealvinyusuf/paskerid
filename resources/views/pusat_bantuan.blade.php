@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h1 class="fw-bold section-title mb-2">Pusat Bantuan</h1>
        <p class="text-muted mb-0">Permasalahan Yang Sering Ditanyakan</p>
    </div>

    <div class="faq-wrapper">
        <div class="faq-category mb-4">
            <h4 class="fw-bold mb-3">Pendaftaran Akun</h4>
            <ol class="ps-3">
                <li class="mb-4">
                    <h6 class="fw-bold">Bagaimana cara mendaftar akun SIAPkerja?</h6>
                    <p class="mb-2">Untuk mendaftar akun SIAPkerja:</p>
                    <ul>
                        <li>Buka website <a href="https://kemnaker.go.id" target="_blank" rel="noopener">kemnaker.go.id</a>.</li>
                        <li>Klik tombol Daftar di pojok kanan atas.</li>
                        <li>Pilih Daftar Sekarang.</li>
                        <li>Isi data sesuai KTP dan KK karena sistem sudah terintegrasi dengan Dukcapil.</li>
                        <li>Ikuti langkah pendaftaran sampai selesai.</li>
                    </ul>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa akun SIAPkerja saya belum terverifikasi?</h6>
                    <p class="mb-2">Akun yang belum terverifikasi biasanya karena profil belum lengkap. Solusinya:</p>
                    <ul>
                        <li>Login ke akun SIAPkerja.</li>
                        <li>Pilih menu Edit Profil.</li>
                        <li>Lengkapi seluruh data hingga semua tahapan selesai.</li>
                    </ul>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa kode OTP tidak diterima?</h6>
                    <p class="mb-2">Hal ini dapat terjadi karena:</p>
                    <ul>
                        <li>Nomor telepon yang terdaftar tidak aktif.</li>
                        <li>OTP dikirim ke email atau nomor telepon yang berbeda.</li>
                    </ul>
                    <p class="mb-2">Solusi:</p>
                    <ul>
                        <li>Pastikan nomor/email yang terdaftar masih aktif.</li>
                        <li>Periksa notifikasi apakah OTP dikirim ke email atau nomor HP.</li>
                    </ul>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa NIK saya sudah terdaftar?</h6>
                    <p class="mb-2">Jika NIK sudah terdaftar berarti sebelumnya sudah pernah membuat akun SIAPkerja.</p>
                    <p class="mb-2">Solusi:</p>
                    <ul>
                        <li>Gunakan fitur Lupa Password jika email atau nomor masih aktif.</li>
                        <li>Jika email/nomor sudah tidak aktif, hubungi admin pusat dengan melampirkan scan KTP untuk reset password.</li>
                    </ul>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Bagaimana mengetahui NIK saya terdaftar dengan email atau nomor apa?</h6>
                    <p class="mb-2">Pengguna dapat:</p>
                    <ul>
                        <li>Menghubungi admin pusat Kemnaker.</li>
                        <li>
                            Mengirim email ke <a href="mailto:pengaduanwlkp.bspk@gmail.com">pengaduanwlkp.bspk@gmail.com</a>
                            dengan melampirkan scan KTP untuk pengecekan.
                        </li>
                    </ul>
                </li>
            </ol>
        </div>

        <div class="faq-category mb-4">
            <h4 class="fw-bold mb-3">Masalah Undangan dan Registrasi</h4>
            <ol class="ps-3" start="6">
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa undangan pengelola akun tidak diterima?</h6>
                    <p class="mb-2">Kemungkinan karena:</p>
                    <ul>
                        <li>Email pengelola belum terdaftar di SIAPkerja.</li>
                        <li>Tautan undangan sudah kedaluwarsa (24 jam).</li>
                    </ul>
                    <p class="mb-2">Solusi:</p>
                    <ul>
                        <li>Pastikan email sudah terdaftar di SIAPkerja.</li>
                        <li>Minta pengiriman ulang undangan melalui email pengaduan WLKP.</li>
                    </ul>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa registrasi perusahaan gagal?</h6>
                    <p class="mb-2">Biasanya karena:</p>
                    <ul>
                        <li>Perusahaan sudah pernah terdaftar.</li>
                        <li>Link undangan dari OSS sudah kedaluwarsa.</li>
                    </ul>
                    <p class="mb-2">Solusi:</p>
                    <ul>
                        <li>Kirim email pengelola baru, nama perusahaan, dan dokumen NIB ke admin WLKP untuk pengiriman ulang undangan.</li>
                    </ul>
                </li>
            </ol>
        </div>

        <div class="faq-category mb-4">
            <h4 class="fw-bold mb-3">Pendaftaran Perusahaan</h4>
            <ol class="ps-3" start="8">
                <li class="mb-4">
                    <h6 class="fw-bold">Bagaimana jika kode pendaftaran WLKP masih dalam proses?</h6>
                    <p class="mb-0">Hubungi admin pusat WLKP atau kirim email dengan melampirkan dokumen NIB perusahaan.</p>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Bagaimana cara mendaftarkan yayasan atau KPPA?</h6>
                    <p class="mb-2">Pendaftaran dilakukan secara manual dengan:</p>
                    <ul>
                        <li>Mengisi form pada link pendaftaran WLKP.</li>
                        <li>Mengunggah dokumen seperti:</li>
                        <li>NIB</li>
                        <li>NPWP</li>
                        <li>Akta pendirian</li>
                        <li>Alamat perusahaan</li>
                        <li>Data pemilik dan pengurus</li>
                    </ul>
                    <p class="mb-0">Admin pusat akan membuat akun WLKP dan mengirim undangan pengelolaan akun.</p>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa NPWP tidak valid saat pendaftaran?</h6>
                    <p class="mb-2">Hal ini biasanya karena status perusahaan di OSS tidak sesuai.</p>
                    <p class="mb-2">Solusi:</p>
                    <ul>
                        <li>Cek di <a href="https://oss.go.id" target="_blank" rel="noopener">oss.go.id</a> menggunakan NIB.</li>
                        <li>Pastikan Status Keaktifan: Aktif.</li>
                        <li>Pastikan Status Migrasi: OSS RBA.</li>
                        <li>Jika tidak sesuai, hubungi layanan OSS.</li>
                    </ul>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa NIB tidak ditemukan di sistem WLKP?</h6>
                    <p class="mb-2">Solusi:</p>
                    <ul>
                        <li>Kirim dokumen NIB lengkap ke admin WLKP agar dilakukan update data pada sistem.</li>
                    </ul>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa KBLI tidak muncul saat pengisian?</h6>
                    <p class="mb-2">Perubahan atau penambahan KBLI hanya dapat dilakukan oleh admin pusat.</p>
                    <p class="mb-0">Perusahaan perlu mengirim dokumen NIB yang memuat KBLI kepada admin.</p>
                </li>
            </ol>
        </div>

        <div class="faq-category mb-4">
            <h4 class="fw-bold mb-3">Kantor Cabang</h4>
            <ol class="ps-3" start="13">
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa kantor cabang tidak muncul di akun pusat?</h6>
                    <p class="mb-2">Hal ini bisa terjadi karena kantor cabang mendaftar lebih dahulu dari kantor pusat.</p>
                    <p class="mb-2">Solusi:</p>
                    <ul>
                        <li>Hubungi admin pusat dengan mencantumkan kode pendaftaran kantor pusat.</li>
                        <li>Hubungi admin pusat dengan mencantumkan kode pendaftaran kantor cabang.</li>
                    </ul>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Bagaimana menambahkan kantor cabang baru?</h6>
                    <p class="mb-0">Pastikan cabang sudah terdaftar pada lampiran NIB di OSS. Jika belum, tambahkan alamat cabang terlebih dahulu di OSS.</p>
                </li>
            </ol>
        </div>

        <div class="faq-category mb-0">
            <h4 class="fw-bold mb-3">Pelaporan WLKP</h4>
            <ol class="ps-3" start="15">
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa laporan tidak bisa dicetak?</h6>
                    <p class="mb-2">Pastikan semua data sudah terisi lengkap.</p>
                    <p class="mb-2">Langkah mencetak laporan:</p>
                    <ul>
                        <li>Selesaikan seluruh pengisian data.</li>
                        <li>Masuk menu Pelaporan.</li>
                        <li>Klik Buat Laporan.</li>
                        <li>Klik Pakta Integritas.</li>
                        <li>Pilih Cetak Laporan.</li>
                    </ul>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa halaman review tidak bisa dilanjutkan?</h6>
                    <p class="mb-2">Karena masih ada data yang belum diisi.</p>
                    <p class="mb-2">Solusi:</p>
                    <ul>
                        <li>Kembali ke dashboard.</li>
                        <li>Lengkapi data yang memiliki tanda bintang merah.</li>
                    </ul>
                </li>
                <li class="mb-0">
                    <h6 class="fw-bold">Mengapa muncul notifikasi "village harus diisi"?</h6>
                    <p class="mb-2">Artinya data kelurahan/desa alamat perusahaan belum lengkap.</p>
                    <p class="mb-2">Solusi:</p>
                    <ul>
                        <li>Isi sesuai alamat pada NIB, atau</li>
                        <li>Minta admin pusat melakukan update data.</li>
                    </ul>
                </li>
            </ol>
        </div>
    </div>
</div>
@endsection

@push('head')
<style>
    .faq-wrapper {
        max-width: 980px;
        margin: 0 auto;
    }

    .faq-category {
        background: #ffffff;
        border: 1px solid #e8ecef;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
    }

    .faq-category h6 {
        color: #13416b;
    }

    .faq-category p,
    .faq-category li {
        line-height: 1.6;
    }
</style>
@endpush
