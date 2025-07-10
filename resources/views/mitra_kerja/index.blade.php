@extends('layouts.app') {{-- Pastikan punya file layouts.app berisi navbar + footer --}}

@section('content')
<section class="py-4 bg-light">
    <section class="section-berita">
    <img src="/images/logo_siapkerja.svg" class="section-bg" alt="background">
    <div class="section-content">
        <h2 style="color: white; font-weight: bold; margin-left: 80px;">Informasi</h2>
    <p style="color: white; margin-left: 80px; font-size: 20px">Dapatkan informasi mengenai kami dimanapun dan kapanpun, kami siap membanti Anda!</p>
    </div>
    </section>

    <div class="circle-wrapper">
    <button class="circle-btn filled">Dinas <br> Tenaga <br> Kerja</button>

    <img src="/images/logo.png" alt="Maskot" class="bird-image">

    <button class="circle-btn outlined">Mitra <br> Pasker</button>
</div>

        {{-- Stakeholder Cards --}}
        <div class="bg-green p-4 rounded-4">
            <h5 class="fw-bold mb-3">Stakeholder Dinas Tenaga Kerja</h5>
            <div class="row">
                @foreach ($stakeholders as $stakeholder)
                    <div class="col-md-4 mb-4">
                        <div class="p-3 shadow-sm rounded-4 bg-white h-100">
                            <h6 class="fw-bold mb-2">Pusat</h6>
                            <p class="mb-1">{{ $stakeholder['name'] }}</p>
                            <p class="mb-1">{{ $stakeholder['address'] }}</p>
                            <p class="mb-1">Telepon: {{ $stakeholder['contact'] }}</p>
                            <p class="mb-1">Email: {{ $stakeholder['email'] }}</p>
                            <a href="#" class="text-success">Kunjungi Website</a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="text-center">
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled"><span class="page-link">«</span></li>
                        <li class="page-item active"><span class="page-link">1</span></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </nav>
            </div>
        </div>

        {{-- Kantor Pusat --}}
        <div class="my-5" style="margin-left: 10px;">
            <h1 class="fw-bold">Kantor Pusat</h1>
            <div class="row">
                <div class="col-md-6">
                    <p><i class="fa fa-map-marker-alt me-2"></i> Gedung Pasar Kerja</p>
                    <p><i class="fa fa-phone me-2"></i> 0811-871-2018</p>
                    <p><i class="fa fa-envelope me-2"></i> pusatpasarkerja@kemnaker.go.id</p>
                </div>
                <div class="col-md-6">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.211894308172!2d106.82165157499043!3d-6.235776693752427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3a463211d29%3A0xd532fe78919d1589!2sLabor%20Market%20Center!5e0!3m2!1sen!2sid!4v1752117534527!5m2!1sen!2sid" width="100%" height="200" class="rounded-4"></iframe>
                    {{-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.211894308172!2d106.82165157499043!3d-6.235776693752427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3a463211d29%3A0xd532fe78919d1589!2sLabor%20Market%20Center!5e0!3m2!1sen!2sid!4v1752117534527!5m2!1sen!2sid" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> --}}
                </div>
            </div>
        </div>

        {{-- Hotline --}}
        <div class="bg-light p-4 rounded-4">
            <h5 class="fw-bold mb-3">Layanan Hotline Pusat Pasar Kerja <br> Jam Pelayanan: 09.00 - 17.00</h5>
            <div class="row text-center">
                <div class="col-md-3 mb-2">
                    <i class="fa fa-phone fa-2x mb-2 text-success"></i>
                    <div>Penempatan Dalam Negeri</div>
                    <div>08123456789</div>
                </div>
                <div class="col-md-3 mb-2">
                    <i class="fa fa-phone fa-2x mb-2 text-success"></i>
                    <div>Penempatan Luar Negeri</div>
                    <div>08123456789</div>
                </div>
                <div class="col-md-3 mb-2">
                    <i class="fa fa-phone fa-2x mb-2 text-success"></i>
                    <div>Perlindungan Pekerja</div>
                    <div>08123456789</div>
                </div>
                <div class="col-md-3 mb-2">
                    <i class="fa fa-phone fa-2x mb-2 text-success"></i>
                    <div>Pusat Kerja</div>
                    <div>08123456789</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

<style>
    .bg-green{
        background-color: rgba(0, 163, 138, 0.2);
    }

    .section-berita {
    position: relative;
    background-color: #00a78e;
    padding: 30px 40px;
    overflow: hidden;
}

.section-bg {
    position: absolute;
    top: 0;
    right: 0;
    width: 400px; /* Perbesar sesuai kebutuhan */
    height: 100%;
    object-fit: cover;
    opacity: 0.4; /* Sesuaikan jika ingin transparan atau hapus jika full color */
}

.section-content {
    position: relative; /* Agar teks tidak ketimpa gambar */
    z-index: 1;
    max-width: 1200px;
}

.section-breadcrumb {
    font-size: 18px;
    margin-bottom: 10px;
}

.section-subtitle {
    font-weight: bold;
    font-size: 18px;
    color: white;
}

.text-primary {
    color: white;
}

.text-white {
    color: white;
}

.fw-bold {
    font-weight: bold;
}
.circle-wrapper {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 32px;
}

.circle-btn {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    font-weight: bold;
    font-size: 16px;
    text-align: center;
    line-height: 1.4;
    padding: 20px;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.3s ease;
    z-index: 1; /* biar tombol tetap di bawah burung */
}

.circle-btn.filled {
    background-color: #00A38A;
    color: white;
    border: 4px solid #00A38A;
}

.circle-btn.outlined {
    background-color: white;
    color: #00A38A;
    border: 4px solid #00A38A;
}

.circle-btn:hover {
    transform: scale(1.05);
}

/* Gambar burung */
.bird-image {
    position: absolute;
    bottom: -20px;
    width: 100px; /* Sesuaikan ukurannya */
    z-index: 2; /* burung ada di atas tombol */
}

</style>
