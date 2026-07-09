<div class="mb-4">
    <nav aria-label="Dashboard navigation">
        <div class="d-flex flex-wrap gap-2">
            <a
                href="{{ route('dashboard.performance') }}"
                class="btn {{ request()->routeIs('dashboard.performance') ? 'btn-primary' : 'btn-outline-primary' }}"
            >
                Struktur Ketenagakerjaan
            </a>
            <a
                href="{{ route('dashboard.trend') }}"
                class="btn {{ request()->routeIs('dashboard.trend') ? 'btn-primary' : 'btn-outline-primary' }}"
            >
                Kebutuhan Tenaga Kerja
            </a>
            <a
                href="{{ route('dashboard.distribution') }}"
                class="btn {{ request()->routeIs('dashboard.distribution') ? 'btn-primary' : 'btn-outline-primary' }}"
            >
                Persediaan Tenaga Kerja
            </a>
        </div>
    </nav>
</div>
