@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <span class="cf-hero-icon"><i class="fa-solid fa-lock"></i></span>
                    <h1 class="h4 fw-bold mb-2">CF (🚧)</h1>
                    <p class="text-muted mb-4">
                        Menu ini sementara dibatasi passcode untuk tahap pengembangan.
                    </p>

                    <form method="POST" action="{{ route('cf.verify-passcode') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="passcode" class="form-label">Passcode</label>
                            <input
                                id="passcode"
                                name="passcode"
                                type="password"
                                class="form-control @error('passcode') is-invalid @enderror"
                                placeholder="Masukkan passcode"
                                required
                                autofocus
                            >
                            @error('passcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Buka Menu CF
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
