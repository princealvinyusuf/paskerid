@push('head')
<style>
    .dashboard-floating-nav-shell {
        position: fixed;
        right: 1rem;
        bottom: 1rem;
        z-index: 1045;
        width: min(320px, calc(100vw - 1.5rem));
    }

    .dashboard-floating-nav {
        border-radius: 18px;
        border: 1px solid rgba(24, 124, 25, 0.22);
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(8px);
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.18);
        padding: 0.85rem;
        transition: transform 0.24s ease, opacity 0.2s ease;
    }

    .dashboard-floating-nav-toggle {
        position: absolute;
        left: -18px;
        top: 50%;
        transform: translateY(-50%);
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.7);
        background: #f1f5f9;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.18);
        transition: transform 0.2s ease, background-color 0.2s ease;
        z-index: 2;
    }

    .dashboard-floating-nav-toggle:hover {
        background: #e2e8f0;
        transform: translateY(-50%) scale(1.03);
    }

    .dashboard-floating-nav-shell.is-collapsed .dashboard-floating-nav {
        transform: translateX(calc(100% + 0.6rem));
        opacity: 0;
        pointer-events: none;
    }

    .dashboard-floating-nav-shell.is-collapsed {
        width: 0;
    }

    .dashboard-floating-nav-shell.is-collapsed .dashboard-floating-nav-toggle {
        left: auto;
        right: 0;
    }

    .dashboard-floating-nav-shell.is-collapsed .dashboard-floating-nav-toggle i {
        transform: rotate(180deg);
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
        .dashboard-floating-nav-shell {
            right: 0.75rem;
            left: 0.75rem;
            width: auto;
            bottom: 0.75rem;
        }

        .dashboard-floating-nav {
            padding: 0.7rem;
        }

        .dashboard-floating-nav__item {
            font-size: 0.88rem;
        }

        .dashboard-floating-nav-toggle {
            left: -14px;
        }

        .dashboard-floating-nav-shell.is-collapsed {
            left: auto;
            right: 0.75rem;
        }
    }
</style>
@endpush

<div class="dashboard-floating-nav-shell" id="dashboardFloatingNavShell">
    <button
        type="button"
        class="dashboard-floating-nav-toggle"
        id="dashboardFloatingNavToggle"
        aria-label="Hide dashboard navigation"
        aria-expanded="true"
        title="Hide/Show navigation"
    >
        <i class="fa-solid fa-chevron-left"></i>
    </button>
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
</div>

@push('scripts')
<script>
    (function () {
        const shell = document.getElementById('dashboardFloatingNavShell');
        const toggle = document.getElementById('dashboardFloatingNavToggle');
        const storageKey = 'dashboard_nav_collapsed';

        if (!shell || !toggle) {
            return;
        }

        const applyCollapsedState = function (isCollapsed) {
            shell.classList.toggle('is-collapsed', isCollapsed);
            toggle.setAttribute('aria-expanded', isCollapsed ? 'false' : 'true');
            toggle.setAttribute('aria-label', isCollapsed ? 'Show dashboard navigation' : 'Hide dashboard navigation');
        };

        const savedCollapsed = localStorage.getItem(storageKey) === '1';
        applyCollapsedState(savedCollapsed);

        toggle.addEventListener('click', function () {
            const collapsed = !shell.classList.contains('is-collapsed');
            applyCollapsedState(collapsed);
            localStorage.setItem(storageKey, collapsed ? '1' : '0');
        });
    })();
</script>
@endpush
