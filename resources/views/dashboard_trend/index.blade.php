@extends('layouts.app')

@section('content')
<div class="container my-5 dashboard-trend-container">
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
    min-height: 90vh;
}
.dashboard-trend-container .tableauPlaceholder,
.dashboard-trend-container .tableauPlaceholder > object,
.dashboard-trend-container .tableauPlaceholder iframe {
    width: 100% !important;
    height: calc(100vh - 180px) !important;
    overflow: hidden !important;
}
</style>
@endpush
@endsection