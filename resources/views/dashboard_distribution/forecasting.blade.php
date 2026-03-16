@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="mb-0">Dashboard Forecasting Persediaan Tenaga Kerja</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard.distribution') }}" class="btn btn-outline-success">Persediaan</a>
            <a href="{{ route('dashboard.distribution.forecasting') }}" class="btn btn-success active">Forecasting</a>
        </div>
    </div>

    @if(!($forecastPayload['ready'] ?? false))
        <div class="alert alert-warning mb-0">
            {{ $forecastPayload['message'] ?? 'Data forecasting belum tersedia.' }}
        </div>
    @else
        @php
            $meta = $forecastPayload['meta'] ?? [];
            $series = $forecastPayload['series'] ?? [];
            $table = $forecastPayload['table'] ?? [];
        @endphp

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Periode Historis</div>
                        <div class="h4 mb-0">{{ number_format((int) ($meta['history_months'] ?? 0), 0, ',', '.') }} bulan</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Bulan Aktual Terakhir</div>
                        <div class="h6 mb-0">{{ $meta['last_actual_month'] ?? '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Rata-rata 6 Bulan Terakhir</div>
                        <div class="h4 mb-0">{{ number_format((int) ($meta['avg_last_6_months'] ?? 0), 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Rata-rata Forecast 6 Bulan</div>
                        <div class="h4 mb-0">{{ number_format((int) ($meta['forecast_avg_next_6_months'] ?? 0), 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                    <h5 class="mb-0">Tren Historis dan Forecast Persediaan Tenaga Kerja</h5>
                    <span class="badge text-bg-light">
                        YoY: {{ isset($meta['yoy_growth_pct']) ? number_format((float) $meta['yoy_growth_pct'], 2, ',', '.') . '%' : '-' }}
                    </span>
                </div>
                <p class="text-muted mb-3">{{ $forecastPayload['method'] ?? '' }}</p>
                <canvas id="forecastTrendChart" height="110"></canvas>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Proyeksi 6 Bulan Ke Depan</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th class="text-end">Forecast Persediaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($table as $row)
                                <tr>
                                    <td>{{ $row['period'] ?? '-' }}</td>
                                    <td class="text-end">{{ number_format((int) ($row['value'] ?? 0), 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@if($forecastPayload['ready'] ?? false)
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var historyLabels = @json($series['history_labels'] ?? []);
        var historyCounts = @json($series['history_counts'] ?? []);
        var forecastLabels = @json($series['forecast_labels'] ?? []);
        var forecastCounts = @json($series['forecast_counts'] ?? []);

        var allLabels = historyLabels.concat(forecastLabels);
        var historyLine = historyCounts.concat(new Array(forecastCounts.length).fill(null));

        var forecastLine = new Array(Math.max(historyCounts.length - 1, 0)).fill(null);
        if (historyCounts.length > 0) {
            forecastLine.push(historyCounts[historyCounts.length - 1]);
        }
        forecastLine = forecastLine.concat(forecastCounts);

        var el = document.getElementById('forecastTrendChart');
        if (!el || typeof Chart === 'undefined') {
            return;
        }

        new Chart(el, {
            type: 'line',
            data: {
                labels: allLabels,
                datasets: [
                    {
                        label: 'Historis',
                        data: historyLine,
                        borderColor: '#0f5fa8',
                        backgroundColor: 'rgba(15, 95, 168, 0.15)',
                        pointRadius: 2,
                        pointHoverRadius: 4,
                        tension: 0.25
                    },
                    {
                        label: 'Forecast',
                        data: forecastLine,
                        borderColor: '#00a38a',
                        backgroundColor: 'rgba(0, 163, 138, 0.16)',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        borderDash: [8, 6],
                        tension: 0.25
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function (ctx) {
                                var val = ctx.parsed.y || 0;
                                return ctx.dataset.label + ': ' + val.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return Number(value).toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
    </script>
    @endpush
@endif
