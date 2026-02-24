@extends('layouts.app')

@section('content')
<div class="container my-5" style="max-width: 560px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h1 class="h4 fw-bold mb-3">Login</h1>
            <p class="text-muted">Login untuk melamar pekerjaan miniJobi di dalam sistem.</p>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <input type="hidden" name="job_id" value="{{ old('job_id', $jobId) }}">

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button class="btn btn-success w-100">Login</button>
            </form>

            <div class="text-center mt-3">
                <small>Belum punya akun? <a href="{{ route('register', ['job' => $jobId]) }}">Register</a></small>
            </div>
        </div>
    </div>
</div>
@endsection

