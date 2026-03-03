<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @php $forceDesktop = request()->cookie('force_desktop') === '1'; @endphp
    @php $isCfRoute = request()->routeIs('cf.*'); @endphp
    @if($forceDesktop)
    <meta name="viewport" content="width=980, user-scalable=no">
    @else
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @endif
    <title>Pasker ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @if($isCfRoute)
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            body.cf-theme {
                font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
                background: radial-gradient(980px 520px at -10% -10%, rgba(59, 130, 246, 0.24), transparent 58%),
                            radial-gradient(920px 520px at 110% -6%, rgba(16, 185, 129, 0.22), transparent 60%),
                            radial-gradient(760px 430px at 50% 105%, rgba(168, 85, 247, 0.14), transparent 62%),
                            #f2f7ff;
                color: #0f172a;
            }

            body.cf-theme .container.py-5 {
                position: relative;
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }

            body.cf-theme .card {
                position: relative;
                overflow: hidden;
                border-radius: 18px !important;
                border: 1px solid rgba(148, 163, 184, 0.2) !important;
                box-shadow: 0 12px 32px rgba(15, 23, 42, 0.1) !important;
                background: linear-gradient(165deg, rgba(255, 255, 255, 0.96), rgba(247, 250, 255, 0.92));
                transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
            }
            body.cf-theme .card::before {
                content: "";
                position: absolute;
                inset: 0;
                pointer-events: none;
                background: linear-gradient(
                    130deg,
                    rgba(59, 130, 246, 0.08) 0%,
                    rgba(59, 130, 246, 0) 35%,
                    rgba(16, 185, 129, 0.06) 68%,
                    rgba(168, 85, 247, 0.08) 100%
                );
                opacity: 0.78;
            }
            body.cf-theme .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 18px 38px rgba(15, 23, 42, 0.14) !important;
                border-color: rgba(99, 102, 241, 0.28) !important;
            }
            body.cf-theme .card .card-body {
                position: relative;
                z-index: 1;
                padding: 1.1rem 1.2rem;
            }

            body.cf-theme h1,
            body.cf-theme h2,
            body.cf-theme h3,
            body.cf-theme h4,
            body.cf-theme h5,
            body.cf-theme h6 {
                color: #0b1220;
                letter-spacing: -0.02em;
                line-height: 1.25;
            }
            body.cf-theme p {
                line-height: 1.6;
            }

            body.cf-theme .btn {
                border-radius: 12px !important;
                font-weight: 600;
                transition: all 0.2s ease;
            }
            body.cf-theme .btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 18px rgba(15, 23, 42, 0.15);
            }
            body.cf-theme .btn:focus-visible {
                outline: none;
                box-shadow: 0 0 0 0.24rem rgba(59, 130, 246, 0.2) !important;
            }

            body.cf-theme .btn-success,
            body.cf-theme .btn-primary {
                background: linear-gradient(135deg, #2563eb, #10b981) !important;
                border-color: #1d4ed8 !important;
                color: #ffffff !important;
            }
            body.cf-theme .btn-outline-success {
                border-color: #10b981 !important;
                color: #047857 !important;
            }
            body.cf-theme .btn-outline-success:hover {
                background: #10b981 !important;
                color: #ffffff !important;
            }

            body.cf-theme .btn-outline-primary {
                border-color: #3b82f6 !important;
                color: #1d4ed8 !important;
            }
            body.cf-theme .btn-outline-primary:hover {
                background: #3b82f6 !important;
                color: #ffffff !important;
            }

            body.cf-theme .btn-outline-danger {
                border-color: #ef4444 !important;
                color: #b91c1c !important;
            }
            body.cf-theme .btn-outline-danger:hover {
                background: #ef4444 !important;
                color: #ffffff !important;
            }
            body.cf-theme .btn-outline-warning {
                border-color: #f59e0b !important;
                color: #b45309 !important;
            }
            body.cf-theme .btn-outline-warning:hover {
                background: #f59e0b !important;
                color: #ffffff !important;
            }
            body.cf-theme .btn-outline-info {
                border-color: #06b6d4 !important;
                color: #0e7490 !important;
            }
            body.cf-theme .btn-outline-info:hover {
                background: #06b6d4 !important;
                color: #ffffff !important;
            }
            body.cf-theme .btn-outline-dark {
                border-color: #475569 !important;
                color: #334155 !important;
            }
            body.cf-theme .btn-outline-dark:hover {
                background: #334155 !important;
                color: #ffffff !important;
            }

            body.cf-theme .form-control,
            body.cf-theme .form-select,
            body.cf-theme textarea {
                border-radius: 12px !important;
                border: 1px solid #dbe3ef !important;
                background-color: #ffffff;
                padding: 0.55rem 0.75rem;
                box-shadow: none !important;
            }
            body.cf-theme .form-control:focus,
            body.cf-theme .form-select:focus,
            body.cf-theme textarea:focus {
                border-color: #60a5fa !important;
                box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.18) !important;
            }

            body.cf-theme .badge {
                border-radius: 999px;
                font-weight: 600;
                padding: 0.4em 0.72em;
                box-shadow: 0 4px 10px rgba(15, 23, 42, 0.12);
            }

            body.cf-theme .alert {
                border-radius: 14px;
                border: none;
            }

            body.cf-theme .pagination .page-link {
                border-radius: 10px !important;
                margin: 0 2px;
                border: 1px solid #dbe3ef;
            }
            body.cf-theme .pagination .page-item.active .page-link {
                background: #2563eb;
                border-color: #2563eb;
            }

            body.cf-theme .text-muted {
                color: #5b6578 !important;
            }

            body.cf-theme .cf-hero {
                border-radius: 18px;
                padding: 1.25rem 1.35rem;
                border: 1px solid rgba(148, 163, 184, 0.2);
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.96), rgba(239, 246, 255, 0.94), rgba(236, 253, 245, 0.9));
                box-shadow: 0 14px 34px rgba(15, 23, 42, 0.1);
            }
            body.cf-theme .cf-toolbar {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                align-items: center;
            }
            body.cf-theme .cf-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 0.2rem 0.55rem;
                align-items: center;
            }

            body.cf-theme .cf-soft {
                background: linear-gradient(145deg, rgba(255, 255, 255, 0.82), rgba(240, 249, 255, 0.72));
                border: 1px solid rgba(99, 102, 241, 0.14);
                border-radius: 14px;
                transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            }
            body.cf-theme .cf-soft:hover {
                transform: translateY(-1px);
                border-color: rgba(99, 102, 241, 0.28);
                box-shadow: 0 12px 26px rgba(15, 23, 42, 0.12);
            }

            body.cf-theme .cf-section-title {
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                color: #475569;
                text-transform: uppercase;
            }

            body.cf-theme .cf-pill {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                padding: 0.25rem 0.7rem;
                border-radius: 999px;
                font-size: 0.78rem;
                font-weight: 600;
                color: #1e293b;
                background: linear-gradient(135deg, #dbeafe, #dcfce7);
                border: 1px solid rgba(59, 130, 246, 0.16);
            }

            body.cf-theme .cf-hero-icon {
                width: 38px;
                height: 38px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
                color: #1e40af;
                background: linear-gradient(135deg, #dbeafe, #f5d0fe, #dcfce7);
                border: 1px solid rgba(99, 102, 241, 0.28);
                box-shadow: 0 8px 20px rgba(59, 130, 246, 0.2);
                margin-bottom: 0.4rem;
            }

            body.cf-theme .cf-empty-state {
                border: 1px dashed rgba(148, 163, 184, 0.5);
                border-radius: 14px;
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.82), rgba(239, 246, 255, 0.9));
                padding: 1.1rem;
                text-align: center;
                color: #64748b;
            }

            body.cf-theme .cf-empty-state i {
                font-size: 1.15rem;
                margin-bottom: 0.35rem;
                display: inline-block;
                color: #64748b;
            }

            body.cf-theme .cf-list-item {
                transition: background-color 0.2s ease, transform 0.2s ease, border-left-color 0.2s ease;
                border-left: 3px solid transparent;
            }
            body.cf-theme .cf-list-item:hover {
                background: linear-gradient(90deg, rgba(239, 246, 255, 0.9), rgba(240, 253, 250, 0.75));
                border-left-color: #60a5fa;
            }

            body.cf-theme .cf-link-lift {
                transition: color 0.2s ease;
            }
            body.cf-theme .cf-link-lift:hover {
                color: #1d4ed8 !important;
            }

            body.cf-theme a:focus-visible,
            body.cf-theme button:focus-visible,
            body.cf-theme input:focus-visible,
            body.cf-theme textarea:focus-visible,
            body.cf-theme select:focus-visible {
                outline: 2px solid rgba(59, 130, 246, 0.35);
                outline-offset: 2px;
            }

            @media (max-width: 991.98px) {
                body.cf-theme .card .card-body {
                    padding: 1rem 1rem;
                }
                body.cf-theme .cf-hero {
                    padding: 1rem 1rem;
                }
                body.cf-theme .h3,
                body.cf-theme h3 {
                    font-size: 1.38rem;
                }
            }

            @media (max-width: 767.98px) {
                body.cf-theme .container.py-5 {
                    padding-top: 1.25rem !important;
                    padding-bottom: 1.5rem !important;
                }
                body.cf-theme .h4,
                body.cf-theme h4 {
                    font-size: 1.18rem;
                }
                body.cf-theme .h5,
                body.cf-theme h5 {
                    font-size: 1.02rem;
                }
                body.cf-theme .btn {
                    width: 100%;
                }
                body.cf-theme .cf-toolbar .btn {
                    width: auto;
                }
            }
        </style>
    @endif
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
<body class="{{ $isCfRoute ? 'cf-theme' : '' }}">
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
        <div class="container-fluid d-flex flex-row justify-content-between align-items-center px-0">
            <!-- Left: Kemnaker Logo and Text -->
            <a class="navbar-brand d-flex align-items-center ms-0 ps-2" href="/">
                <img src="{{ asset('images/logo_kemnaker.png') }}" alt="Kemnaker Logo" style="height:40px; width:auto;">
                <span class="ms-2 fw-bold d-none d-md-inline" style="font-family: inherit; color: #13416B; font-size:1.05rem; letter-spacing:0.5px;">
                    Kementerian<br>Ketenagakerjaan RI
                </span>
            </a>
            <!-- Navbar links -->
            <div class="collapse navbar-collapse w-100" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-1 me-2">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('home')) active fw-bold @endif" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('informasi_pasar_kerja.index')) active fw-bold @endif" href="{{ route('informasi_pasar_kerja.index') }}">Informasi Pasar Kerja</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle @if(request()->routeIs('virtual-karir.index')) active fw-bold @endif" href="#" id="layananDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Layanan
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="layananDropdown">
                            <li><a class="dropdown-item @if(request()->routeIs('virtual-karir.index')) active fw-bold @endif" href="{{ route('virtual-karir.index') }}">Virtual Karir</a></li>
                            <li><a class="dropdown-item" href="https://microlearning-paskerid.kemnaker.go.id/" target="_blank">Microlearning</a></li>
                            <li><a class="dropdown-item" href="https://paskerid.kemnaker.go.id/RIASEC/" target="_blank">Tes Minat & Karir</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle @if(request()->routeIs('datasets.index') || (request()->routeIs('informasi.index') && request('type') == 'publikasi')) active fw-bold @endif" href="#" id="publikasiDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Publikasi</a>
                        <ul class="dropdown-menu" aria-labelledby="publikasiDropdown">
                            <li><a class="dropdown-item @if(request()->routeIs('informasi.index') && request('type') == 'publikasi') active fw-bold @endif" href="{{ route('informasi.index', ['type' => 'publikasi']) }}">Publikasi Pasar Kerja</a></li>
                            <li><a class="dropdown-item @if(request()->routeIs('datasets.index')) active fw-bold @endif" href="{{ route('datasets.index') }}">Dataset Pasar Kerja</a></li>
                        </ul>
                    </li>
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
                        <a class="nav-link @if(request()->routeIs('career-boostday.index')) active fw-bold @endif" href="{{ route('career-boostday.index') }}">
                            Career Boost Day
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('cf.*')) active fw-bold @endif" href="{{ route('cf.gate') }}">
                            CF (🚧)
                        </a>
                    </li>
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="akunDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Akun
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="akunDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil Saya</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    @endauth
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('about')) active fw-bold @endif" href="{{ route('about') }}">Tentang Kami</a>
                    </li>
                </ul>
            </div>
            <!-- Right: Pasker ID Logo and Text -->
            <div class="d-flex align-items-center me-0 pe-2">
                <img src="{{ asset('images/logo.png') }}" alt="Paskerid Logo" style="height:40px; width:auto;">
                <img src="{{ asset('images/Logo_Pasker_ID.png') }}" alt="Paskerid Logo" style="height:40px; width:auto;">
                <!-- <span class="fw-bold ms-2 d-none d-lg-inline" style="font-family: inherit; color: inherit;">Pasker ID</span> -->
            </div>
            <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success mb-0">
                @if(is_string(session('success')))
                    {{ session('success') }}
                @else
                    Operasi berhasil dilakukan.
                @endif
            </div>
        </div>
    @endif
    <main>
        @yield('content')
        <!-- Back to Top Button -->
        <button id="backToTopBtn" class="btn btn-success rounded-circle" style="position: fixed; bottom: 32px; left: 32px; display: none; z-index: 9999; width:48px; height:48px; box-shadow: 0 4px 16px rgba(40,167,69,0.18);">
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
                        <div><a href="{{ route('minijobi.index') }}" class="text-white text-decoration-none">miniJobi</a></div>
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
                    <a href="https://twitter.com/pusatpasarkerja" target="_blank" class="text-white mx-1"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="https://www.facebook.com/pusatpasarkerja" target="_blank" class="text-white mx-1"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="https://www.youtube.com/pusatpasarkerja" target="_blank" class="text-white mx-1"><i class="fab fa-youtube fa-lg"></i></a>
                    <a href="https://www.tiktok.com/@paskerid_" target="_blank" class="text-white mx-1"><i class="fab fa-tiktok fa-lg"></i></a>
                </div>
                <div class="small">Copyright © 2026 Pusat Pasar Kerja Indonesia</div>
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
    .navbar .nav-link,
    .navbar .dropdown-item {
        font-size: 0.8rem;
    }
    .navbar .nav-link {
        padding-left: 0.45rem;
        padding-right: 0.45rem;
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