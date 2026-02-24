@extends('layouts.app')

@section('content')
<div class="container my-5" style="max-width: 560px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h1 class="h4 fw-bold mb-3">Register</h1>
            <p class="text-muted">Buat akun terlebih dahulu untuk melamar pekerjaan di miniJobi.</p>

            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                <input type="hidden" name="job_id" value="{{ old('job_id', $jobId) }}">

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button class="btn btn-success w-100">Register</button>
            </form>

            <div class="text-center mt-3">
                <small>Sudah punya akun? <a href="{{ route('login', ['job' => $jobId]) }}">Login</a></small>
            </div>
        </div>
    </div>
</div>
@endsection

