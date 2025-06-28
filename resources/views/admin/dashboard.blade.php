@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Admin Dashboard</h2>
    <div class="alert alert-info">Selamat datang di halaman admin. Kelola konten website Anda di sini.</div>
    <ul class="list-group list-group-flush mb-4">
        <li class="list-group-item"><a href="{{ route('admin.news.index') }}">Kelola Berita</a></li>
        {{-- Tambahkan link ke resource admin lain di sini --}}
    </ul>
</div>
@endsection 