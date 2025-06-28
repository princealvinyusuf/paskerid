@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Edit Profil</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}">
        </div>
        <div class="mb-3">
            <label>Password Baru (opsional)</label>
            <input type="password" name="password" class="form-control">
            <small class="text-muted">Isi hanya jika ingin mengganti password</small>
        </div>
        <div class="mb-3">
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
    <form action="{{ route('profile.destroy') }}" method="POST" class="mt-4" onsubmit="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.')">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">Hapus Akun</button>
    </form>
</div>
@endsection
