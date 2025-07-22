<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasker ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @yield('head')
    @stack('head')
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-VVRKTYE9YB"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-VVRKTYE9YB');
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Paskerid Logo" style="height:40px; width:auto;">
                <span class="fw-bold ms-2" style="font-family: inherit; color: inherit;">Pasker ID</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-4">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('home')) active fw-bold @endif" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('informasi_pasar_kerja.index')) active fw-bold @endif" href="{{ route('informasi_pasar_kerja.index') }}">Informasi Pasar Kerja</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle @if(request()->routeIs('virtual-karir.index') || request()->routeIs('mitra_kerja.index') || request()->routeIs('informasi.index')) active fw-bold @endif" href="#" id="layananDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Layanan
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="layananDropdown">
                            <li><a class="dropdown-item @if(request()->routeIs('virtual-karir.index')) active fw-bold @endif" href="{{ route('virtual-karir.index') }}">Virtual Karir</a></li>
                            <!-- <li><a class="dropdown-item @if(request()->routeIs('mitra_kerja.index')) active fw-bold @endif" href="{{ route('mitra_kerja.index') }}">Informasi Mitra Kerja</a></li> -->
                            <!-- <li><a class="dropdown-item @if(request()->routeIs('informasi.index')) active fw-bold @endif" href="{{ route('informasi.index') }}">Data</a></li> -->
                            <li><a class="dropdown-item" href="https://microlearning-paskerid.kemnaker.go.id/" target="_blank">Microlearning</a></li>
                        </ul>
                    </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle @if(request()->routeIs('dashboard.trend') || request()->routeIs('dashboard.distribution') || request()->routeIs('dashboard.performance') || request()->routeIs('dashboard.labor_demand')) active fw-bold @endif" href="#" id="monitoringDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dashboard
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="monitoringDropdown">
                            <li><a class="dropdown-item @if(request()->routeIs('dashboard.trend')) active fw-bold @endif" href="{{ route('dashboard.trend') }}">Tren Informasi Kebutuhan Tenaga Kerja</a></li>
                            <li><a class="dropdown-item @if(request()->routeIs('dashboard.distribution')) active fw-bold @endif" href="{{ route('dashboard.distribution') }}">Tren Pencari Kerja</a></li>
                            <li><a class="dropdown-item @if(request()->routeIs('dashboard.performance')) active fw-bold @endif" href="{{ route('dashboard.performance') }}">Dashboard Performance</a></li>
                            <li><a class="dropdown-item @if(request()->routeIs('dashboard.labor_demand')) active fw-bold @endif" href="{{ route('dashboard.labor_demand') }}">Dashboard Labor Demand</a></li>
                        </ul>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('news.index')) active fw-bold @endif" href="{{ route('news.index') }}">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('media_sosial')) active fw-bold @endif" href="{{ route('media_sosial') }}">Media Sosial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('kemitraan.create')) active fw-bold @endif" href="{{ route('kemitraan.create') }}">
                            <i class="bi bi-handshake me-1"></i> Walk In Interview
                        </a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('about')) active fw-bold @endif" href="{{ route('about') }}">Tentang Kami</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
        <!-- Back to Top Button -->
        <button id="backToTopBtn" class="btn btn-success rounded-circle" style="position: fixed; bottom: 32px; right: 32px; display: none; z-index: 9999; width:48px; height:48px; box-shadow: 0 4px 16px rgba(40,167,69,0.18);">
            <i class="fa fa-arrow-up"></i>
        </button>
    </main>


    <footer class="bg-success text-white py-4">
    <div class="container">
        <div class="row gy-4">
            <!-- Logo & Description -->
            <div class="col-12 col-lg-3 d-flex flex-column align-items-lg-start align-items-center text-center text-lg-start mb-3 mb-lg-0">
                <img src="{{ asset('images/logo_kemnaker.png') }}" alt="Logo Kemnaker" class="footer-logo mb-2">
                <div style="font-size:0.97rem; line-height:1.4;">
                    <span class="fw-bold">Pusat Pasar Kerja Indonesia (Pasker ID)</span><br>
                    Merupakan unit kerja yang diinisiasi oleh Kementerian Ketenagakerjaan Republik Indonesia dalam mempertemukan pencari kerja dan pemberi kerja secara digital, terintegrasi, dan inklusif.
                </div>
            </div>
            <!-- Menu Columns -->
            <div class="col-12 col-lg-7">
                <div class="row text-center text-lg-start">
                    <div class="col-6 col-md-3 mb-3 mb-md-0">
                        <div class="fw-bold mb-2">Informasi</div>
                        <div><a href="{{ route('informasi_pasar_kerja.index') }}" class="text-white text-decoration-none">Informasi Pasar Kerja</a></div>
                    </div>
                    <div class="col-6 col-md-3 mb-3 mb-md-0">
                        <div class="fw-bold mb-2">Layanan</div>
                        <div><a href="{{ route('virtual-karir.index') }}" class="text-white text-decoration-none">Virtual Karir</a></div>
                        
                        <!-- <div><a href="{{ route('informasi.index') }}" class="text-white text-decoration-none">Data</a></div> -->
                        <div><a href="https://microlearning-paskerid.kemnaker.go.id/" target="_blank" class="text-white text-decoration-none">Microlearning</a></div>
                    </div>
                    <div class="col-6 col-md-3 mb-3 mb-md-0">
                        <div class="fw-bold mb-2">Tentang Kami</div>
                        <div><a href="{{ route('about') }}" class="text-white text-decoration-none">Pasker ID</a></div>
                        <div><a href="{{ route('mitra_kerja.index') }}" class="text-white text-decoration-none">Informasi Mitra Kerja</a></div>
                        <div><a href="{{ route('kebijakan_privasi') }}" class="text-white text-decoration-none">Kebijakan Privasi</a></div>
                        <div><a href="{{ route('ketentuan_pengguna') }}" class="text-white text-decoration-none">Ketentuan Pengguna</a></div>
                    </div>
                    <div class="col-6 col-md-3 mb-3 mb-md-0">
                        <div class="fw-bold mb-2">Alamat</div>
                        <div><a class="text-white text-decoration-none">Jl. Gatot Subroto No.44, Kota Jakarta Selatan, 12710</a></div>
                    </div>
                </div>
            </div>
            <!-- Social & Copyright -->
            <div class="col-12 col-lg-2 d-flex flex-column align-items-center align-items-lg-end justify-content-end mt-3 mt-lg-0">
                <div class="mb-2">
                    <a href="https://www.instagram.com/pusatpasarkerja/?hl=en" target="_blank" class="text-white mx-1"><i class="fab fa-instagram fa-lg"></i></a>
                </div>
                <div class="small">Copyright © 2025 Pusat Pasar Kerja Indonesia</div>
            </div>
        </div>
    </div>
</footer>






    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    @stack('scripts')
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
        // Back to Top Button logic
        const backToTopBtn = document.getElementById('backToTopBtn');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
    @push('head')
    <style>
    html, body {
        height: 100%;
        min-height: 100%;
    }
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    main {
        flex: 1 0 auto;
    }
    main > .container {
        min-height: 60vh;
    }
    .bg-success {
        background-color: #00A38A !important;
    }
    :root {
        --primary-green: #187C19;
        --secondary-green: #69B41E;
        --accent-green: #8DC71E;
        --light-green: #B8D53D;
        --dark-green: #0D5B11;
    }

    .btn-success, .btn-primary {
        background-color: var(--primary-green) !important;
        border-color: var(--primary-green) !important;
    }
    .btn-success:hover, .btn-primary:hover {
        background-color: var(--secondary-green) !important;
        border-color: var(--secondary-green) !important;
    }
    .navbar, .sticky-top {
        background-color: var(--primary-green) !important;
    }
    .navbar .nav-link, .navbar-brand {
        color: #fff !important;
    }
    .navbar .nav-link.active, .navbar .nav-link:focus, .navbar .nav-link:hover {
        color: var(--light-green) !important;
    }
    a, .text-success {
        color: var(--primary-green) !important;
    }
    a:hover, .text-success:hover {
        color: var(--secondary-green) !important;
    }
    .section-title {
        color: var(--primary-green);
    }
    /* footer.bg-light {
        background-color: var(--light-green) !important;
        color: var(--dark-green) !important;
    } */
    #backToTopBtn {
        opacity: 0.85;
        transition: opacity 0.2s, transform 0.2s;
    }
    #backToTopBtn:hover {
        opacity: 1;
        transform: scale(1.08);
    }
    footer a {
    white-space: nowrap;  /* Paksa 1 baris */
}
footer {
    margin-top: auto;
}

    .statistik-nav {
        background: #00A38A !important;
        color: #fff !important;
        border-radius: 0.5rem;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(0, 163, 138, 0.18);
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        margin-left: 0.5rem;
        margin-right: 0.5rem;
        transition: background 0.2s, color 0.2s;
        border: 2px solid #187C19 !important;
    }
    .statistik-nav.active, .statistik-nav:focus, .statistik-nav:hover {
        background: #187C19 !important;
        color: #fff !important;
        border-color: #00A38A !important;
    }

    .footer-logo {
        height: 24px;
        width: auto;
        opacity: 0.6;
        background: none !important;
        border-radius: 0;
        padding: 0;
        transition: opacity 0.2s;
    }
    .footer-logo:hover {
        opacity: 0.8;
    }

    </style>
    @endpush
</body>
</html> 