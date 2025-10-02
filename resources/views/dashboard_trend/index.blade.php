@extends('layouts.app')

@section('content')
<div class="container py-3 dashboard-trend-container">
    <h2>Dashboard Kebutuhan Tenaga Kerja</h2>
    @if($dashboard)
        {!! $dashboard->iframe_code !!}
    @else
        <p>No dashboard trend available.</p>
    @endif
</div>
@push('head')
<style>
.dashboard-trend-container {
    padding-top: 1rem;
    padding-bottom: 1rem;
    overflow: hidden;
}
.dashboard-trend-container .tableauPlaceholder,
.dashboard-trend-container .tableauPlaceholder > object,
.dashboard-trend-container .tableauPlaceholder iframe {
    width: 100% !important;
    height: calc(100vh - 220px) !important;
    overflow: hidden !important;
}
</style>
@endpush
@push('scripts')
<script>
(function() {
    function clampHeight(value) {
        return Math.max(320, Math.floor(value));
    }
    function resizeDashboardTrend() {
        var container = document.querySelector('.dashboard-trend-container');
        if (!container) return;
        var placeholder = container.querySelector('.tableauPlaceholder');
        if (!placeholder) return;
        var navbar = document.querySelector('.navbar.sticky-top');
        var footer = document.querySelector('footer');
        var heading = container.querySelector('h2');
        var viewport = window.innerHeight || document.documentElement.clientHeight;
        var navH = navbar ? navbar.offsetHeight : 0;
        var footH = footer ? footer.offsetHeight : 0;
        var headingH = heading ? heading.offsetHeight : 0;
        var styles = getComputedStyle(container);
        var verticalPadding = parseFloat(styles.paddingTop) + parseFloat(styles.paddingBottom);
        var available = clampHeight(viewport - navH - footH - headingH - verticalPadding - 12);

        placeholder.style.setProperty('height', available + 'px', 'important');
        var obj = placeholder.querySelector('object');
        if (obj) obj.style.setProperty('height', available + 'px', 'important');
        var iframe = placeholder.querySelector('iframe');
        if (iframe) iframe.style.setProperty('height', available + 'px', 'important');

        container.style.setProperty('min-height', (available + headingH + verticalPadding) + 'px', 'important');
    }
    window.addEventListener('load', resizeDashboardTrend);
    window.addEventListener('resize', resizeDashboardTrend);
    var obs = new MutationObserver(function() { resizeDashboardTrend(); });
    obs.observe(document.body, { childList: true, subtree: true });
})();
</script>
@endpush
@endsection