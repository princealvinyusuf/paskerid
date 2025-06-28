<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Paskerid') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    @yield('head')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Paskerid Logo" style="height:40px; width:auto;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('home')) active fw-bold @endif" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('informasi.index')) active fw-bold @endif" href="{{ route('informasi.index') }}">Informasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('news.index')) active fw-bold @endif" href="{{ route('news.index') }}">News</a>
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
    <footer class="bg-light text-center py-4 mt-5">
        <div class="container">
            <small>&copy; {{ date('Y') }} Paskerid. All rights reserved.</small>
        </div>
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
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
    #backToTopBtn {
        opacity: 0.85;
        transition: opacity 0.2s, transform 0.2s;
    }
    #backToTopBtn:hover {
        opacity: 1;
        transform: scale(1.08);
    }
    .btn:focus, .btn:focus-visible {
        outline: none !important;
        box-shadow: 0 0 0 0.2rem rgba(40,167,69,0.25) !important;
    }
    .btn-success:focus, .btn-success:focus-visible, .btn-outline-success:focus, .btn-outline-success:focus-visible {
        box-shadow: 0 0 0 0.25rem rgba(40,167,69,0.45) !important;
    }
    .btn-primary:focus, .btn-primary:focus-visible {
        box-shadow: 0 0 0 0.25rem rgba(0,123,255,0.45) !important;
    }
    .nav-link:focus, .nav-link:focus-visible {
        outline: none !important;
        box-shadow: 0 2px 0 0 #28a745;
        background: rgba(40,167,69,0.08);
        border-radius: 0.5rem;
    }
    </style>
    @endpush
</body>
</html> 