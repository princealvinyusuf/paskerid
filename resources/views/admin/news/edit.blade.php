@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Edit Berita</h2>
    <form action="{{ route('admin.news.update', $news) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="title" class="form-control" required value="{{ old('title', $news->title) }}">
        </div>
        <div class="mb-3">
            <label>Isi</label>
            <textarea name="content" class="form-control" rows="5" required>{{ old('content', $news->content) }}</textarea>
        </div>
        <div class="mb-3">
            <label>URL Gambar (opsional)</label>
            <input type="url" name="image_url" class="form-control" value="{{ old('image_url', $news->image_url) }}">
        </div>
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="date" class="form-control" required value="{{ old('date', $news->date) }}">
        </div>
        <div class="mb-3">
            <label>Penulis (opsional)</label>
            <input type="text" name="author" class="form-control" value="{{ old('author', $news->author) }}">
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
