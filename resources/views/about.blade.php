@extends('layouts.app')

@section('content')
<div class="container my-5">
    @foreach($sections as $section)
        <section class="mb-5">
            <h2 class="mb-4 text-center">{{ $section->title }}</h2>
            @if($section->type === 'text_image')
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div>{!! nl2br(e($section->content)) !!}</div>
                    </div>
                    <div class="col-md-6">
                        <img src="{{ asset($section->media_url) }}" class="img-fluid rounded shadow" alt="{{ $section->title }}">
                    </div>
                </div>
            @elseif($section->type === 'image')
                <div class="text-center">
                    <img src="{{ asset($section->media_url) }}" class="img-fluid rounded shadow" alt="{{ $section->title }}">
                </div>
            @elseif($section->type === 'youtube')
                <div class="ratio ratio-16x9">
                    {!! $section->media_url !!}
                </div>
            @endif
        </section>
    @endforeach
</div>
@endsection 