@extends('layouts.app')

@section('content')
<style>
.info-page-ocean {
    --info-ocean-accent: #0f5fa8;
    --info-ocean-secondary: #00a38a;
    --info-ocean-tertiary: #2f9fe8;
}

.info-page-ocean-wrap {
    background: radial-gradient(980px 520px at -10% -10%, rgba(37, 99, 235, 0.2), transparent 58%),
        radial-gradient(920px 520px at 110% -6%, rgba(16, 185, 129, 0.16), transparent 60%),
        radial-gradient(760px 430px at 50% 105%, rgba(47, 159, 232, 0.13), transparent 62%),
        #f2f7ff;
    padding-top: 1.5rem;
    padding-bottom: 2rem;
}

.info-page-ocean .card {
    position: relative;
    overflow: hidden;
    border-radius: 18px !important;
    border: 1px solid rgba(15, 95, 168, 0.2) !important;
    box-shadow: 0 12px 30px rgba(12, 50, 82, 0.1) !important;
    background: linear-gradient(165deg, rgba(255, 255, 255, 0.97), rgba(244, 251, 255, 0.94)) !important;
}

.info-page-ocean .card::before {
    content: "";
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: linear-gradient(
        130deg,
        rgba(47, 159, 232, 0.1) 0%,
        rgba(15, 95, 168, 0.08) 30%,
        rgba(0, 163, 138, 0.08) 72%,
        rgba(47, 159, 232, 0.12) 100%
    );
    opacity: 0.75;
}

.info-page-ocean .card > * {
    position: relative;
    z-index: 1;
}

.info-page-ocean .card-header {
    background: linear-gradient(120deg, rgba(15, 95, 168, 0.96) 0%, rgba(47, 159, 232, 0.95) 48%, rgba(0, 163, 138, 0.9) 100%) !important;
    color: #fff;
    border-bottom: 0;
}

.info-page-ocean .list-group-item a.bg-primary.text-white {
    background: linear-gradient(120deg, rgba(15, 95, 168, 0.96) 0%, rgba(47, 159, 232, 0.95) 48%, rgba(0, 163, 138, 0.9) 100%) !important;
    color: #fff !important;
}

.info-page-ocean .nav-tabs {
    border-bottom-color: rgba(15, 95, 168, 0.2);
}

.info-page-ocean .nav-tabs .nav-link {
    color: var(--info-ocean-accent);
    border-radius: 999px;
}

.info-page-ocean .nav-tabs .nav-link.active {
    color: #fff;
    border-color: transparent;
    background: linear-gradient(120deg, rgba(15, 95, 168, 0.96) 0%, rgba(47, 159, 232, 0.95) 48%, rgba(0, 163, 138, 0.9) 100%);
}

.info-page-ocean .table {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(15, 95, 168, 0.15);
}

.info-page-ocean .table thead.table-light th {
    background: rgba(230, 244, 255, 0.95) !important;
    color: #0f5fa8;
}

.info-page-ocean .table-hover > tbody > tr:hover > * {
    background-color: rgba(47, 159, 232, 0.09);
}
</style>
<div class="info-page-ocean-wrap">
<div class="container mt-5 info-page-ocean">
    <h2 class="text-center mb-4">Publikasi Pasar Kerja</h2>
    <p class="text-center mb-4">Menyediakan tabel dan publikasi pasar kerja yang dikelompokkan ke dalam berbagai kategori</p>
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header fw-bold">Subject</div>
                <ul class="list-group list-group-flush">
                    @foreach($subjects as $subject)
                        <li class="list-group-item p-0">
                            <a id="subject-{{ \Illuminate\Support\Str::slug($subject) }}" href="?subject={{ urlencode($subject) }}&type={{ request('type', 'statistik') }}" class="d-block px-3 py-2 @if($selectedSubject == $subject) bg-primary text-white @else text-dark @endif" style="text-decoration:none;">
                                {{ $subject }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            @php
                // Determine if there is data for each tab
                $hasStatistik = \App\Models\Information::where('subject', $selectedSubject)->where('type', 'statistik')->exists();
                $hasPublikasi = \App\Models\Information::where('subject', $selectedSubject)->where('type', 'publikasi')->exists();
                $currentType = request('type', 'statistik');
                // If only one tab has data and the current type is not that tab, redirect
                if ($hasStatistik && !$hasPublikasi && $currentType !== 'statistik') {
                    header('Location: ?subject=' . urlencode($selectedSubject) . '&type=statistik');
                    exit;
                }
                if ($hasPublikasi && !$hasStatistik && $currentType !== 'publikasi') {
                    header('Location: ?subject=' . urlencode($selectedSubject) . '&type=publikasi');
                    exit;
                }
                // Auto-show detail if coming from Publikasi page and only one result
                if (request('type') === 'publikasi' && request('search') && $information->count() === 1) {
                    $info = $information->first();
                    $showInfo = $info;
                }
            @endphp
            @if($hasStatistik || $hasPublikasi)
            <ul class="nav nav-tabs mb-4" id="infoTab" role="tablist">
                @if($hasStatistik)
                <li class="nav-item" role="presentation">
                    <a class="nav-link @if(request('type', 'statistik') == 'statistik') active @endif" href="?subject={{ urlencode($selectedSubject) }}&type=statistik">Tabel Statistik</a>
                </li>
                @endif
                @if($hasPublikasi)
                <li class="nav-item" role="presentation">
                    <a class="nav-link @if(request('type') == 'publikasi') active @endif" href="?subject={{ urlencode($selectedSubject) }}&type=publikasi">Publikasi</a>
                </li>
                @endif
            </ul>
            @endif
            @if(!isset($showInfo) || !$showInfo)
                <div id="info-table-container">
                    <form method="GET" class="row g-3 mb-4">
                        <input type="hidden" name="type" value="{{ request('type', 'statistik') }}">
                        <input type="hidden" name="subject" value="{{ $selectedSubject }}">
                        <div class="col-md-9">
                            <input type="text" name="search" class="form-control" placeholder="Cari Judul Publikasi atau Tahun Publikasi" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Cari</button>
                        </div>
                    </form>
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">No.</th>
                                <th>Judul Tabel</th>
                                <th style="width: 180px;">Terakhir Diperbarui</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($information as $index => $info)
                                <tr class="info-row" data-id="{{ $info->id }}" data-file-url="{{ $info->file_url }}" style="cursor:pointer;">
                                    <td>{{ $index + 1 + ($information->currentPage() - 1) * $information->perPage() }}</td>
                                    <td>{{ $info->title }}</td>
                                    <td>{{ indo_date($info->date) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada informasi ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $information->withQueryString()->links() }}
                    </div>
                </div>
            @endif
            <div id="dynamic-container" class="mt-4" @if(!isset($showInfo) || !$showInfo) style="display:none;" @endif>
                <div class="card">
                    <div class="card-body">
                        @php
                            $activeDescription = (isset($showInfo) && $showInfo) ? $showInfo->description : $description;
                        @endphp
                        @if(!empty($activeDescription))
                            <div class="alert alert-info mb-3">{{ $activeDescription }}</div>
                        @endif

                        @if(isset($showInfo) && $showInfo)
                            @if(request('type') == 'publikasi' && $showInfo->file_url)
                                <div class="mb-3 d-flex gap-2">
                                    <a href="?subject={{ urlencode($selectedSubject) }}&type=publikasi" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> Back to Table
                                    </a>
                                    <a href="{{ asset($showInfo->file_url) }}" class="btn btn-success" target="_blank" download>
                                        <i class="fa fa-download"></i> Unduh Dokumen
                                    </a>
                                </div>
                                <embed src="{{ asset($showInfo->file_url) }}" type="application/pdf" width="100%" height="600px" />
                            @else
                                <a href="?subject={{ urlencode($selectedSubject) }}&type={{ request('type', 'statistik') }}" class="btn btn-secondary mb-3">
                                    <i class="fa fa-arrow-left"></i> Back to Table
                                </a>
                                {!! $showInfo->iframe_url !!}
                            @endif
                        @else
                            <span id="container-content">Container 1</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.info-row').forEach(function(row) {
            row.addEventListener('click', function() {
                var params = new URLSearchParams(window.location.search);
                params.set('show', this.dataset.id);
                window.location.search = params.toString();
            });
        });

        // Scroll and highlight subject if 'search' param exists
        const params = new URLSearchParams(window.location.search);
        const search = params.get('search');
        if (search) {
            document.querySelectorAll('.list-group-item a').forEach(function(link) {
                if (link.textContent.trim().toLowerCase() === search.trim().toLowerCase()) {
                    link.classList.add('bg-warning', 'text-dark');
                    link.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }

        // If 'subject' param is missing, redirect to include it and remove 'search'
        const ref = document.referrer;
        if (
            search &&
            !params.get('subject') &&
            ref.includes('/informasi_pasar_kerja')
        ) {
            params.set('subject', search);
            params.delete('search');
            window.location.search = params.toString();
        }
    });
</script>
@endsection