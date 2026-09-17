@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">{{ $news->title }}</h2>
    @if($news->image_url)
        <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="img-fluid rounded mb-3" style="max-width:400px;">
    @endif
    <div class="mb-2">
        <strong>Tanggal:</strong> {{ $news->date }}<br>
        <strong>Penulis:</strong> {{ $news->author }}
    </div>
    @php
        $newsContent = (string) ($news->content ?? '');
        if (\Illuminate\Support\Str::contains($newsContent, ['&lt;', '&gt;'])) {
            $newsContent = html_entity_decode($newsContent, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    @endphp
    <div class="mb-4">
        {!! $newsContent !!}
    </div>
    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Kembali</a>
    <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-primary">Edit</a>
</div>
@endsection
