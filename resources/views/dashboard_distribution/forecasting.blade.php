@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="mb-0">Dashboard Forecasting Persediaan Tenaga Kerja</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard.distribution') }}" class="btn btn-outline-success">Persediaan</a>
            <a href="{{ route('dashboard.distribution.forecasting') }}" class="btn btn-success active">Forecasting</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <p class="mb-0">Dashboard Forecasting Persediaan Tenaga Kerja siap ditampilkan di halaman ini.</p>
        </div>
    </div>
</div>
@endsection
