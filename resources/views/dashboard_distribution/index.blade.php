@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="mb-0">Dashboard Persediaan Tenaga Kerja</h2>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#forecastingPasscodeModal">
                Forecasting
            </button>
        </div>
    </div>

    @if($dashboard)
        {!! $dashboard->iframe_code !!}
    @else
        <p>Sedang dikembangkan</p>
    @endif
</div>

<div class="modal fade" id="forecastingPasscodeModal" tabindex="-1" aria-labelledby="forecastingPasscodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forecastingPasscodeModalLabel">Akses Menu Forecasting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('dashboard.distribution.forecasting.verify-passcode') }}">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">Masukkan passcode untuk membuka Dashboard Forecasting Persediaan Tenaga Kerja.</p>
                    <label for="forecasting-passcode" class="form-label">Passcode</label>
                    <input
                        id="forecasting-passcode"
                        name="passcode"
                        type="password"
                        class="form-control @error('passcode') is-invalid @enderror"
                        placeholder="Masukkan passcode"
                        required
                        autofocus
                    >
                    @error('passcode')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Buka Forecasting</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($errors->has('passcode'))
    var modalElement = document.getElementById('forecastingPasscodeModal');
    if (modalElement) {
        var modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
    @endif
});
</script>
@endpush