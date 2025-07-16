@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Data Pasar Kerja</h2>
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
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="start_date" class="form-control" placeholder="Dari tanggal" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="end_date" class="form-control" placeholder="Sampai tanggal" value="{{ request('end_date') }}">
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
                        @if(isset($showInfo) && $showInfo)
                            @if(request('type') == 'publikasi' && $showInfo->file_url)
                                <a href="?subject={{ urlencode($selectedSubject) }}&type=publikasi" class="btn btn-secondary mb-3">
                                    <i class="fa fa-arrow-left"></i> Back to Table
                                </a>
                                @if(!empty($description))
                                    <div class="alert alert-info mb-3">{{ $description }}</div>
                                @endif
                                <embed src="{{ asset($showInfo->file_url) }}" type="application/pdf" width="100%" height="600px" />
                            @else
                                <a href="?subject={{ urlencode($selectedSubject) }}&type={{ request('type', 'statistik') }}" class="btn btn-secondary mb-3">
                                    <i class="fa fa-arrow-left"></i> Back to Table
                                </a>
                                @if(!empty($description))
                                    <div class="alert alert-info mb-3">{{ $description }}</div>
                                @endif
                                {!! $showInfo->iframe_url !!}
                            @endif
                        @else
                            @if(!empty($description))
                                <div class="alert alert-info mb-3">{{ $description }}</div>
                            @endif
                            <span id="container-content">Container 1</span>
                        @endif
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
            // If 'subject' param is missing, redirect to include it
            if (!params.get('subject')) {
                params.set('subject', search);
                window.location.search = params.toString();
            }
        }
    });
</script>
@endsection