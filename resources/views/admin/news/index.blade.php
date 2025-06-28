@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Kelola Berita</h2>
    <a href="{{ route('admin.news.create') }}" class="btn btn-success mb-3">+ Tambah Berita</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Penulis</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($news as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->date }}</td>
                    <td>{{ $item->author }}</td>
                    <td>
                        <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus berita ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Belum ada berita.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $news->links() }}
    </div>
</div>
@endsection
