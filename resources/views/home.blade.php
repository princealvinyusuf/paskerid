@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Hero Section: Statistics --}}
    <section class="hero my-5">
        <div class="row text-center">
            @foreach($statistics as $stat)
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $stat->title }}</h5>
                            <h2 class="display-4">{{ $stat->value }} <small>{{ $stat->unit }}</small></h2>
                            <p class="card-text">{{ $stat->description }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Informasi Terbaru --}}
    <section class="my-5">
        <h3>Informasi Terbaru</h3>
        <ul class="list-group">
            @foreach($information as $info)
                <li class="list-group-item">
                    <strong>{{ $info->title }}</strong> <br>
                    <small>{{ $info->date }}</small>
                    <p>{{ $info->description }}</p>
                    @if($info->file_url)
                        <a href="{{ $info->file_url }}" target="_blank">Download</a>
                    @endif
                </li>
            @endforeach
        </ul>
    </section>

    {{-- Charts Section --}}
    <section class="my-5">
        <h3>Tren Pencari Kerja</h3>
        <div class="row">
            @foreach($charts as $chart)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $chart->title }}</h5>
                            <p>{{ $chart->description }}</p>
                            {{-- Chart rendering placeholder --}}
                            <pre>{{ $chart->data_json }}</pre>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Top Lists Section --}}
    <section class="my-5">
        <h3>Top 5 Lists</h3>
        <div class="row">
            @foreach($topLists as $list)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">{{ $list->title }}</h6>
                            <ul>
                                @foreach(json_decode($list->data_json, true)['items'] as $item)
                                    <li>{{ $item['name'] }} ({{ $item['count'] }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Contributions Section --}}
    <section class="my-5">
        <h3>Kontribusi Pasker</h3>
        <div class="row">
            @foreach($contributions as $contrib)
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fa {{ $contrib->icon }} fa-2x mb-2"></i>
                            <h6 class="card-title">{{ $contrib->title }}</h6>
                            <p>{{ $contrib->description }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Services Section --}}
    <section class="my-5">
        <h3>Layanan Ketenagakerjaan</h3>
        <div class="row">
            @foreach($services as $service)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <i class="fa {{ $service->icon }} fa-2x mb-2"></i>
                            <h6 class="card-title">{{ $service->title }}</h6>
                            <p>{{ $service->description }}</p>
                            @if($service->link)
                                <a href="{{ $service->link }}" target="_blank">Kunjungi</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- News Section --}}
    <section class="my-5">
        <h3>Berita Terkini</h3>
        <div class="row">
            @foreach($news as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" class="card-img-top" alt="{{ $item->title }}">
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">{{ $item->title }}</h6>
                            <p>{{ $item->content }}</p>
                            <small>{{ $item->date }} | {{ $item->author }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Testimonials Section --}}
    <section class="my-5">
        <h3>Testimoni</h3>
        <div class="row">
            @foreach($testimonials as $testi)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($testi->photo_url)
                            <img src="{{ $testi->photo_url }}" class="card-img-top rounded-circle mx-auto mt-3" style="width:80px; height:80px; object-fit:cover;" alt="{{ $testi->name }}">
                        @endif
                        <div class="card-body text-center">
                            <blockquote class="blockquote">
                                <p class="mb-0">"{{ $testi->quote }}"</p>
                                <footer class="blockquote-footer">{{ $testi->name }}, {{ $testi->position }} @ {{ $testi->company }}</footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection 