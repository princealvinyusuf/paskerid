@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Informasi Terbaru</h2>
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header fw-bold">Subject</div>
                <ul class="list-group list-group-flush">
                    @foreach($subjects as $subject)
                        <li class="list-group-item p-0">
                            <a href="?subject={{ urlencode($subject) }}&type={{ request('type', 'statistik') }}" class="d-block px-3 py-2 @if($selectedSubject == $subject) bg-primary text-white @else text-dark @endif" style="text-decoration:none;">
                                {{ $subject }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <ul class="nav nav-tabs mb-4" id="infoTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link @if(request('type', 'statistik') == 'statistik') active @endif" href="?subject={{ urlencode($selectedSubject) }}&type=statistik">Tabel Statistik</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link @if(request('type') == 'publikasi') active @endif" href="?subject={{ urlencode($selectedSubject) }}&type=publikasi">Publikasi</a>
                </li>
            </ul>
            @if(!isset($showInfo) || !$showInfo)
                <div id="info-table-container">
                    <form method="GET" class="row g-3 mb-4">
                        <input type="hidden" name="type" value="{{ request('type', 'statistik') }}">
                        <input type="hidden" name="subject" value="{{ $selectedSubject }}">
                        <div class="col-md-7">
                            <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
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
    });
</script>
@endsection