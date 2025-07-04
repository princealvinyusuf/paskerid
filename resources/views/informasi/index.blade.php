@extends('layouts.app')

@section('content')
<div class="container my-5">
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
            <div id="info-table-container">
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
                            <tr class="info-row" data-row="{{ $index + 1 }}" data-iframe-url="{{ $info->iframe_url }}">
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
            <div id="dynamic-container" class="mt-4" style="display:none;">
                <button id="back-to-table" class="btn btn-secondary mb-3"><i class="fa fa-arrow-left"></i> Back</button>
                <div class="card">
                    <div class="card-body">
                        <iframe id="container-iframe" src="" width="100%" height="400" style="border:none;display:none;"></iframe>
                        <span id="container-content">Container 1</span>
                        <div id="container-embed" style="display:none;"></div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.info-row').forEach(function(row) {
                        row.addEventListener('click', function() {
                            document.getElementById('info-table-container').style.display = 'none';
                            document.getElementById('dynamic-container').style.display = 'block';
                            var iframeUrl = this.dataset.iframeUrl;
                            var iframe = document.getElementById('container-iframe');
                            var embedDiv = document.getElementById('container-embed');
                            var contentSpan = document.getElementById('container-content');
                            if (iframeUrl) {
                                if (/^https?:\/\//.test(iframeUrl)) {
                                    iframe.src = iframeUrl;
                                    iframe.style.display = 'block';
                                    embedDiv.style.display = 'none';
                                    contentSpan.style.display = 'none';
                                } else {
                                    iframe.src = '';
                                    iframe.style.display = 'none';
                                    embedDiv.innerHTML = iframeUrl;
                                    embedDiv.style.display = 'block';
                                    contentSpan.style.display = 'none';
                                }
                            } else {
                                iframe.src = '';
                                iframe.style.display = 'none';
                                embedDiv.innerHTML = '';
                                embedDiv.style.display = 'none';
                                contentSpan.style.display = 'block';
                                contentSpan.textContent = 'No URL available';
                            }
                        });
                    });
                    document.getElementById('back-to-table').addEventListener('click', function() {
                        document.getElementById('dynamic-container').style.display = 'none';
                        document.getElementById('info-table-container').style.display = 'block';
                    });
                });
            </script>
            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">Kembali ke Beranda <i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
    </div>
</div>
@endsection 