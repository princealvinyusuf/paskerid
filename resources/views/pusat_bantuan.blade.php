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

        <div class="faq-category mb-4">
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

        <div class="faq-category mb-4">
            <h4 class="fw-bold mb-3">Perubahan Data Perusahaan</h4>
            <ol class="ps-3" start="18">
                <li class="mb-4">
                    <h6 class="fw-bold">Bagaimana jika terjadi perubahan nama atau alamat perusahaan?</h6>
                    <p class="mb-2">Hubungi admin WLKP dengan melampirkan:</p>
                    <ul>
                        <li>NIB terbaru.</li>
                        <li>Dokumen perubahan perusahaan.</li>
                    </ul>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Bagaimana jika terjadi perubahan pemilik atau pengurus?</h6>
                    <p class="mb-2">Lampirkan:</p>
                    <ul>
                        <li>Struktur organisasi perusahaan.</li>
                        <li>Akta pendirian perusahaan.</li>
                    </ul>
                    <p class="mb-0">Admin pusat akan melakukan pembaruan data.</p>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Bagaimana jika tanggal berdiri perusahaan salah?</h6>
                    <p class="mb-0">Kirim Akta Pendirian Perusahaan kepada admin WLKP untuk pembaruan data.</p>
                </li>
                <li class="mb-0">
                    <h6 class="fw-bold">Bagaimana jika terjadi perubahan KBLI?</h6>
                    <p class="mb-0">Kirim dokumen NIB terbaru kepada admin WLKP untuk dilakukan update.</p>
                </li>
            </ol>
        </div>

        <div class="faq-category mb-4">
            <h4 class="fw-bold mb-3">Perpindahan atau Penutupan Perusahaan</h4>
            <ol class="ps-3" start="22">
                <li class="mb-4">
                    <h6 class="fw-bold">Bagaimana mekanisme perpindahan alamat perusahaan?</h6>
                    <p class="mb-2">Langkahnya:</p>
                    <ul>
                        <li>Melapor ke Disnaker setempat.</li>
                        <li>Pengawas membuat Berita Acara Perpindahan.</li>
                        <li>Dokumen disampaikan ke admin WLKP pusat.</li>
                        <li>Admin melakukan update alamat di sistem WLKP.</li>
                    </ul>
                </li>
                <li class="mb-0">
                    <h6 class="fw-bold">Bagaimana cara menutup akun WLKP perusahaan?</h6>
                    <p class="mb-2">Langkahnya:</p>
                    <ul>
                        <li>Melapor ke Disnaker setempat.</li>
                        <li>Melampirkan NIB.</li>
                        <li>Melampirkan Laporan WLKP terakhir.</li>
                        <li>Melampirkan bukti pembayaran hak tenaga kerja.</li>
                        <li>Pengawas membuat Berita Acara Penutupan.</li>
                        <li>Admin WLKP pusat menutup akun perusahaan.</li>
                    </ul>
                </li>
            </ol>
        </div>

        <div class="faq-category mb-4">
            <h4 class="fw-bold mb-3">Data Tenaga Kerja</h4>
            <ol class="ps-3" start="24">
                <li class="mb-4">
                    <h6 class="fw-bold">Bagaimana memilih waktu kerja di sistem WLKP?</h6>
                    <p class="mb-2">Pilih sesuai ketentuan perusahaan:</p>
                    <ul>
                        <li>7 jam/hari (40 jam/minggu), atau</li>
                        <li>8 jam/hari (40 jam/minggu).</li>
                    </ul>
                    <p class="mb-0">Jika sektor khusus tersedia di sistem, pilih sesuai sektor usaha.</p>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa import data tenaga kerja gagal?</h6>
                    <p class="mb-2">Beberapa penyebab umum:</p>
                    <ul>
                        <li>Format Excel tidak sesuai.</li>
                        <li>File bukan format Microsoft Excel 2010 atau lebih baru.</li>
                        <li>Format template tidak sesuai.</li>
                        <li>NIK bukan e-KTP.</li>
                    </ul>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Bagaimana memilih kode jabatan?</h6>
                    <ul>
                        <li>Pilih jabatan yang paling mendekati.</li>
                        <li>Kode jabatan harus 6 digit angka.</li>
                        <li>Mengacu pada KBJI 2016.</li>
                    </ul>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Bagaimana mengisi data tenaga kerja asing?</h6>
                    <p class="mb-2">Langkahnya:</p>
                    <ul>
                        <li>Pilih menu Tenaga Kerja Asing.</li>
                        <li>Isi nomor notifikasi sesuai dokumen RPTKA.</li>
                        <li>Pastikan tenaga kerja pendamping terdaftar di tenaga kerja dalam negeri.</li>
                    </ul>
                </li>
                <li class="mb-0">
                    <h6 class="fw-bold">Mengapa jumlah tenaga kerja tidak sesuai?</h6>
                    <p class="mb-2">Pastikan:</p>
                    <ul>
                        <li>Tidak ada data tenaga kerja ganda.</li>
                        <li>Status kerja sudah benar (PKWT atau PKWTT).</li>
                        <li>Jumlah tenaga kerja sesuai kondisi sebenarnya.</li>
                    </ul>
                </li>
            </ol>
        </div>

        <div class="faq-category mb-4">
            <h4 class="fw-bold mb-3">Lowongan Kerja</h4>
            <ol class="ps-3" start="29">
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa perusahaan tidak bisa mendaftarkan lowongan kerja?</h6>
                    <p class="mb-2">Perusahaan harus menyelesaikan pelaporan WLKP terlebih dahulu hingga tahap pelaporan selesai. Setelah itu perusahaan baru dapat melakukan pembaruan atau pendaftaran lowongan kerja.</p>
                    <p class="mb-0">Jika masih mengalami kendala, silakan menghubungi Call Center KarirHub.</p>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa informasi lowongan kerja tidak dapat diedit?</h6>
                    <p class="mb-0">Perusahaan dapat melakukan pengeditan dengan login ke layanan KarirHub menggunakan akun perusahaan dan melakukan perubahan pada bagian kualifikasi atau informasi lowongan.</p>
                </li>
                <li class="mb-0">
                    <h6 class="fw-bold">Bagaimana perlakuan terhadap tenaga kerja yang sudah tidak bekerja atau terkena PHK?</h6>
                    <p class="mb-2">Tenaga kerja yang sudah tidak bekerja tidak boleh dihapus dari sistem karena akan menghilangkan histori data.</p>
                    <p class="mb-2">Solusi:</p>
                    <ul>
                        <li>Ubah status tenaga kerja dari masih bekerja menjadi tidak bekerja.</li>
                    </ul>
                    <p class="mb-0"><strong>Catatan:</strong> Jika sudah terdapat laporan PHK, data tidak dapat dihapus karena digunakan oleh BPJS Ketenagakerjaan untuk klaim JKP (Jaminan Kehilangan Pekerjaan).</p>
                </li>
            </ol>
        </div>

        <div class="faq-category mb-4">
            <h4 class="fw-bold mb-3">Pelatihan</h4>
            <ol class="ps-3" start="32">
                <li class="mb-4">
                    <h6 class="fw-bold">Apa yang dimaksud dengan Training Center pada sistem WLKP?</h6>
                    <p class="mb-2">Training Center adalah lembaga pelatihan yang dimiliki oleh perusahaan untuk meningkatkan kompetensi tenaga kerja dan biasanya memiliki sertifikasi.</p>
                    <p class="mb-2">Umumnya dimiliki oleh:</p>
                    <ul>
                        <li>Perusahaan penyedia jasa tenaga kerja.</li>
                        <li>Perusahaan jasa layanan K3.</li>
                    </ul>
                </li>
                <li class="mb-0">
                    <h6 class="fw-bold">Apa yang dimaksud dengan program pemagangan pada bagian Training Center?</h6>
                    <p class="mb-2">Program pemagangan adalah program pelatihan kerja yang diselenggarakan perusahaan untuk memberikan pengalaman kerja kepada tenaga kerja.</p>
                    <p class="mb-2">Program ini dapat dilakukan:</p>
                    <ul>
                        <li>Di perusahaan lain.</li>
                        <li>Di luar negeri.</li>
                    </ul>
                    <p class="mb-0">Biasanya dilakukan oleh perusahaan penyedia jasa tenaga kerja.</p>
                </li>
            </ol>
        </div>

        <div class="faq-category mb-4">
            <h4 class="fw-bold mb-3">Jaminan Sosial</h4>
            <ol class="ps-3" start="34">
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa NPP tidak ditemukan saat pengisian?</h6>
                    <p class="mb-0">Periksa terlebih dahulu apakah status keanggotaan BPJS Ketenagakerjaan perusahaan masih aktif atau terdapat tunggakan.</p>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa jumlah tenaga kerja pada NPP tidak sesuai dengan data BPJS?</h6>
                    <p class="mb-2">Karena sistem WLKP terintegrasi dengan BPJS Ketenagakerjaan.</p>
                    <p class="mb-2">Jika terjadi ketidaksesuaian:</p>
                    <ul>
                        <li>Hubungi admin pusat.</li>
                        <li>Kirim bukti pembayaran BPJS yang mencantumkan jumlah tenaga kerja terdaftar.</li>
                    </ul>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa data BPJS Kesehatan belum terisi?</h6>
                    <p class="mb-0">Saat ini sistem WLKP belum terintegrasi dengan BPJS Kesehatan, sehingga pengisian jumlah tenaga kerja yang terdaftar masih dilakukan secara manual.</p>
                </li>
                <li class="mb-0">
                    <h6 class="fw-bold">Apa yang dimaksud dengan fasilitas kesejahteraan?</h6>
                    <p class="mb-2">Fasilitas kesejahteraan adalah fasilitas yang diberikan perusahaan untuk meningkatkan kesejahteraan pekerja, seperti:</p>
                    <ul>
                        <li>Perumahan pekerja.</li>
                        <li>Kendaraan pekerja.</li>
                        <li>Koperasi karyawan.</li>
                        <li>Kegiatan family gathering.</li>
                        <li>Fasilitas lain yang diberikan perusahaan.</li>
                    </ul>
                </li>
            </ol>
        </div>

        <div class="faq-category mb-0">
            <h4 class="fw-bold mb-3">Persyaratan Kerja</h4>
            <ol class="ps-3" start="38">
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa tidak bisa mengisi layanan PP/PKB?</h6>
                    <p class="mb-2">Layanan PP/PKB hanya tersedia bagi perusahaan yang memiliki lokasi kerja di lebih dari satu provinsi.</p>
                    <p class="mb-0">Jika perusahaan memiliki PP/PKB, nomor dokumen akan muncul otomatis dalam sistem.</p>
                </li>
                <li class="mb-4">
                    <h6 class="fw-bold">Mengapa jumlah tenaga kerja PKWT dan PKWTT tidak bisa diedit?</h6>
                    <p class="mb-0">Jumlah tersebut mengikuti data tenaga kerja yang diinput pada bagian data tenaga kerja, sehingga tidak dapat diubah secara manual.</p>
                </li>
                <li class="mb-0">
                    <h6 class="fw-bold">Bagaimana cara mengisi WKWI umum dan sektoral?</h6>
                    <p class="mb-2">Pengisian dilakukan sesuai ketentuan waktu kerja yang berlaku di perusahaan.</p>
                    <ul>
                        <li>Jika perusahaan berada pada sektor tertentu, pilih WKWI sektoral.</li>
                        <li>Jika tidak termasuk sektor khusus, gunakan WKWI umum.</li>
                    </ul>
                    <p class="mb-0">Bagian fasilitas kesejahteraan dan informasi lain harus diisi sesuai kondisi riil perusahaan.</p>
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
