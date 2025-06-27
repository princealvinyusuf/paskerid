@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    {{-- Hero Banner Section --}}
    <section class="hero-banner position-relative text-white mb-5" style="background: url('{{ asset('images/hero-bg.jpg') }}') center center/cover no-repeat; min-height: 320px;">
        <div class="container h-100 d-flex flex-column justify-content-center align-items-center" style="min-height: 320px; background: rgba(0,0,0,0.4);">
            <h1 class="display-4 fw-bold mb-3 text-center">Selamat Datang di Paskerid</h1>
            <p class="lead mb-4 text-center">Platform data pasar kerja Indonesia terlengkap dan terpercaya.</p>
            <a href="#informasi-terbaru" class="btn btn-primary btn-lg">Jelajahi Informasi</a>
        </div>
    </section>

    {{-- Hero Section: Statistics --}}
    <section class="hero my-5">
        <div class="row text-center justify-content-center">
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
    <section class="my-5" id="informasi-terbaru">
        <h3 class="text-center mb-4">Informasi Terbaru</h3>
        <ul class="nav nav-tabs justify-content-center mb-4" id="infoTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="statistik-tab" data-bs-toggle="tab" data-bs-target="#statistik" type="button" role="tab" aria-controls="statistik" aria-selected="true">Tabel Statistik</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="publikasi-tab" data-bs-toggle="tab" data-bs-target="#publikasi" type="button" role="tab" aria-controls="publikasi" aria-selected="false">Publikasi</button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="statistik" role="tabpanel" aria-labelledby="statistik-tab">
                @foreach($information->where('type', 'statistik') as $info)
                    <div class="card mb-3 shadow-sm rounded-pill px-4 py-3 d-flex flex-row align-items-center">
                        <div class="me-3 text-primary" style="font-size:2rem;"><i class="fa fa-clipboard"></i></div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $info->title }}</div>
                            <div class="text-muted small">{{ indo_date($info->date) }}</div>
                        </div>
                        <div>
                            <a href="{{ $info->file_url ?? '#' }}" class="btn btn-link text-success p-0" target="_blank"><i class="fa fa-arrow-right fa-lg"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="tab-pane fade" id="publikasi" role="tabpanel" aria-labelledby="publikasi-tab">
                @foreach($information->where('type', 'publikasi') as $info)
                    <div class="card mb-3 shadow-sm rounded-pill px-4 py-3 d-flex flex-row align-items-center">
                        <div class="me-3 text-primary" style="font-size:2rem;"><i class="fa fa-book"></i></div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $info->title }}</div>
                            <div class="text-muted small">{{ indo_date($info->date) }}</div>
                        </div>
                        <div>
                            <a href="{{ $info->file_url ?? '#' }}" class="btn btn-link text-success p-0" target="_blank"><i class="fa fa-arrow-right fa-lg"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('informasi.index') }}" class="btn btn-outline-success rounded-pill px-4 py-2">Lihat Semua <i class="fa fa-arrow-right"></i></a>
        </div>
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
                            <canvas id="chart-{{ $chart->id }}" height="200"></canvas>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Top 5 Lists Section as Carousel --}}
    <section class="my-5">
        <h3 class="text-center mb-4">Top 5 Lists</h3>
        <div class="card shadow rounded-4 p-4 mb-5" style="max-width: 1100px; margin: 0 auto;">
            <div id="top5Carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="10000">
                <div class="carousel-indicators">
                    @php
                        $topListTypes = [
                            'skills' => [
                                'title' => 'Top 5 Kualifikasi Paling Umum Pencari Kerja',
                                'desc' => 'Menampilkan kualifikasi yang paling banyak dicari oleh pencari kerja.',
                                'icon' => 'fa-user-graduate',
                            ],
                            'provinces' => [
                                'title' => 'Top 5 Provinsi dengan Pencari Kerja Terbanyak',
                                'desc' => 'Memetakan konsentrasi pencari kerja secara geografis.',
                                'icon' => 'fa-map-marked-alt',
                            ],
                            'talents' => [
                                'title' => 'Top 5 Talenta dengan Lowongan Terbanyak',
                                'desc' => 'Talenta yang paling banyak dibutuhkan di pasar kerja.',
                                'icon' => 'fa-users',
                            ],
                            'sectors' => [
                                'title' => 'Top 5 Sektor Industri Pemberi Lowongan Terbanyak',
                                'desc' => 'Sektor industri yang paling banyak membuka lowongan.',
                                'icon' => 'fa-industry',
                            ],
                        ];
                    @endphp
                    @foreach($topListTypes as $type => $meta)
                        <button type="button" data-bs-target="#top5Carousel" data-bs-slide-to="{{ $loop->index }}" @if($loop->first) class="active" aria-current="true" @endif aria-label="{{ $meta['title'] }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach($topListTypes as $type => $meta)
                        @php
                            $list = $topLists->where('type', $type)->first();
                            $items = $list ? json_decode($list->data_json, true)['items'] : [];
                            $date = $list ? $list->date : null;
                        @endphp
                        <div class="carousel-item @if($loop->first) active @endif">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-md-6">
                                    <canvas id="top5-chart-{{ $type }}" height="260"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="fw-bold mb-2"><i class="fa {{ $meta['icon'] }} me-2 text-success"></i>{{ $meta['title'] }}</h4>
                                    <p class="mb-3">{{ $meta['desc'] }}</p>
                                    @if($date)
                                        <div class="text-muted small">Data diperbarui pada {{ indo_date($date) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#top5Carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#top5Carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    {{-- Contributions Section --}}
    <section class="my-5">
        <div class="text-center mb-2">
            <h3 class="fw-bold mb-1">Kontribusi Pasker</h3>
            <h4 class="fw-bold mb-4" style="font-size:2rem;">Untuk Masyarakat Indonesia</h4>
        </div>
        <div class="row justify-content-center g-4">
            @foreach($contributions as $contrib)
                <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch">
                    <div class="contrib-card-v2 card border-0 shadow-sm h-100 p-4 mx-auto">
                        <div class="icon-bg mb-3 d-flex align-items-center justify-content-center mx-auto">
                            <i class="fa {{ $contrib->icon }} fa-2x text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">{{ $contrib->title }}</h5>
                        <p class="text-dark mb-0" style="font-size:1rem;">{{ $contrib->description }}</p>
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

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach($charts as $chart)
                const ctx{{ $chart->id }} = document.getElementById('chart-{{ $chart->id }}').getContext('2d');
                const data{{ $chart->id }} = @json(json_decode($chart->data_json, true));
                new Chart(ctx{{ $chart->id }}, {
                    type: '{{ $chart->chart_type }}',
                    data: {
                        labels: data{{ $chart->id }}.labels,
                        datasets: [{
                            label: '{{ $chart->title }}',
                            data: data{{ $chart->id }}.data,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            @endforeach
            @php
                $topListTypes = ['skills', 'provinces', 'talents', 'sectors'];
            @endphp
            @foreach($topListTypes as $type)
                @php
                    $list = $topLists->where('type', $type)->first();
                    $items = $list ? json_decode($list->data_json, true)['items'] : [];
                @endphp
                const ctxTop5{{ ucfirst($type) }} = document.getElementById('top5-chart-{{ $type }}');
                if (ctxTop5{{ ucfirst($type) }}) {
                    new Chart(ctxTop5{{ ucfirst($type) }}, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode(array_column($items, 'name')) !!},
                            datasets: [{
                                data: {!! json_encode(array_column($items, 'count')) !!},
                                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                                borderColor: 'rgba(40, 167, 69, 1)',
                                borderWidth: 2,
                                borderRadius: 8,
                                maxBarThickness: 24,
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            plugins: {
                                legend: { display: false },
                                tooltip: { enabled: true }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: { color: '#333' },
                                    grid: { display: false }
                                },
                                y: {
                                    ticks: { color: '#333' },
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                }
            @endforeach
        });
    </script>
@endsection

@push('head')
<style>
.contrib-card {
    transition: box-shadow 0.2s, transform 0.2s;
    border-radius: 1.5rem;
    background: #fff;
}
.contrib-card:hover {
    box-shadow: 0 8px 32px rgba(40,167,69,0.12), 0 1.5px 6px rgba(0,0,0,0.06);
    transform: translateY(-4px) scale(1.04);
    z-index: 2;
}

/* Improved v2 style for new design */
.contrib-card-v2 {
    border-radius: 1.5rem;
    background: #fff;
    box-shadow: 0 8px 32px rgba(40,167,69,0.10), 0 1.5px 6px rgba(0,0,0,0.04);
    transition: box-shadow 0.2s, transform 0.2s;
    min-height: 340px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
}
.contrib-card-v2:hover {
    box-shadow: 0 16px 48px rgba(40,167,69,0.16), 0 3px 12px rgba(0,0,0,0.08);
    transform: translateY(-6px) scale(1.03);
    z-index: 2;
}
.icon-bg {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: #4ecb8f;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}
</style>
@endpush 