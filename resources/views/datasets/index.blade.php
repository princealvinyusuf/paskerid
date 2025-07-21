@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold" style="font-size:2.2rem;">
            <i class="fa fa-database text-primary"></i>
            Dataset Pasar Kerja
        </h2>
    </div>
    @php
        $categoryBg = [
            'Pencari Kerja' => 'dataset-bg-yellow',
            'Pemberi Kerja' => 'dataset-bg-blue',
            'Lowongan Kerja' => 'dataset-bg-green',
        ];
    @endphp
    <div class="row justify-content-center g-4">
        @foreach($datasets as $category => $cards)
            <div class="col-12 col-md-4">
                <div class="mb-3 p-3 rounded shadow {{ $categoryBg[$category] ?? '' }}">
                    <h4 class="fw-bold mb-2" style="color: #187C19;">
                        <span class="text-danger">DATASET</span> {{ strtoupper($category) }}
                        @if($cards->first()->icon)
                            <i class="fa {{ $cards->first()->icon }} ms-2"></i>
                        @endif
                    </h4>
                    @foreach($cards as $card)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="fw-bold mb-1">{{ $card->title }}</div>
                                <div class="mb-1 text-muted" style="font-size:0.95rem;">
                                    <i class="fa fa-map-marker"></i> {{ $card->location ?? '-' }}<br>
                                    <i class="fa fa-calendar"></i> {{ $card->years ?? '-' }}
                                </div>
                                <div class="d-flex gap-2 mt-2">
                                    @if($card->csv_url)
                                        <a href="{{ asset($card->csv_url) }}" class="btn btn-warning btn-sm fw-bold" target="_blank">CSV</a>
                                    @endif
                                    @if($card->xlsx_url)
                                        <a href="{{ asset($card->xlsx_url) }}" class="btn btn-success btn-sm fw-bold" target="_blank">XLSX</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('head')
<style>
.dataset-bg-yellow { background: #fffbe6; }
.dataset-bg-blue   { background: #e6f0ff; }
.dataset-bg-green  { background: #e6ffed; }
</style>
@endpush
