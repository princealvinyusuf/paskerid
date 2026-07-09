@push('head')
<style>
    .dashboard-floating-nav {
        position: fixed;
        right: 1rem;
        bottom: 1rem;
        z-index: 1045;
        width: min(320px, calc(100vw - 1.5rem));
        border-radius: 18px;
        border: 1px solid rgba(24, 124, 25, 0.22);
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(8px);
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.18);
        padding: 0.85rem;
    }

    .dashboard-floating-nav__title {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #187C19;
        margin-bottom: 0.55rem;
    }

    .dashboard-floating-nav__list {
        display: grid;
        gap: 0.5rem;
    }

    .dashboard-floating-nav__item {
        width: 100%;
        border-radius: 12px;
        border: 1px solid rgba(24, 124, 25, 0.28);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: flex-start;
        gap: 0.55rem;
        padding: 0.5rem 0.75rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .dashboard-floating-nav__item i {
        width: 1rem;
        text-align: center;
    }

    .dashboard-floating-nav__item:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 16px rgba(24, 124, 25, 0.18);
    }

    @media (max-width: 767.98px) {
        .dashboard-floating-nav {
            right: 0.75rem;
            left: 0.75rem;
            width: auto;
            bottom: 0.75rem;
            padding: 0.7rem;
        }

        .dashboard-floating-nav__item {
            font-size: 0.88rem;
        }
    }
</style>
@endpush

<nav class="dashboard-floating-nav" aria-label="Dashboard navigation">
    <div class="dashboard-floating-nav__title">Dashboard Navigation</div>
    <div class="dashboard-floating-nav__list">
        <a
            href="{{ route('dashboard.performance') }}"
            class="btn dashboard-floating-nav__item {{ request()->routeIs('dashboard.performance') ? 'btn-primary' : 'btn-outline-primary' }}"
        >
            <i class="fa-solid fa-sitemap"></i>
            <span>Struktur Ketenagakerjaan</span>
        </a>
        <a
            href="{{ route('dashboard.trend') }}"
            class="btn dashboard-floating-nav__item {{ request()->routeIs('dashboard.trend') ? 'btn-primary' : 'btn-outline-primary' }}"
        >
            <i class="fa-solid fa-arrow-trend-up"></i>
            <span>Kebutuhan Tenaga Kerja</span>
        </a>
        <a
            href="{{ route('dashboard.distribution') }}"
            class="btn dashboard-floating-nav__item {{ request()->routeIs('dashboard.distribution') ? 'btn-primary' : 'btn-outline-primary' }}"
        >
            <i class="fa-solid fa-chart-pie"></i>
            <span>Persediaan Tenaga Kerja</span>
        </a>
    </div>
</nav>
