@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <h1 class="h4 fw-bold mb-1">Form Hasil Konseling</h1>
                            <div class="text-muted">Silakan isi hasil konseling berikut ini.</div>
                        </div>
                        <div class="text-success fs-3">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="fw-bold mb-2">Mohon periksa kembali isian Anda:</div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('form-hasil-konseling.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="nama_konselor">Nama Konselor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_konselor" name="nama_konselor" value="{{ old('nama_konselor') }}" required list="konselor_list" autocomplete="off">
                            <datalist id="konselor_list">
                                @foreach(($konselorOptions ?? []) as $opt)
                                    <option value="{{ $opt }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="nama_konseli">Nama Konseli/Pencaker <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_konseli" name="nama_konseli" value="{{ old('nama_konseli') }}" required list="konseli_list" autocomplete="off">
                            <datalist id="konseli_list">
                                @foreach(($konseliOptions ?? []) as $opt)
                                    <option value="{{ $opt }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <div id="prefillAlert" class="alert alert-info d-none">
                            Data sebelumnya ditemukan dan sudah diisi otomatis. Silakan cek kembali sebelum mengirim.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="tanggal_konseling">Tanggal Konseling <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_konseling" name="tanggal_konseling" value="{{ old('tanggal_konseling') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="jenis_konseling">Jenis Konseling <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis_konseling" name="jenis_konseling" required>
                                <option value="" disabled {{ old('jenis_konseling') ? '' : 'selected' }}>Choose</option>
                                @foreach($jenisOptions as $opt)
                                    <option value="{{ $opt }}" {{ old('jenis_konseling') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="hal_yang_dibahas">Hal yang Dibahas <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="hal_yang_dibahas" name="hal_yang_dibahas" rows="4" required>{{ old('hal_yang_dibahas') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="saran_untuk_pencaker">Saran untuk Pencaker <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="saran_untuk_pencaker" name="saran_untuk_pencaker" rows="4" required>{{ old('saran_untuk_pencaker') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="bukti">Bukti Konseling</label>
                            <div class="text-muted small mb-2">Jika ada, dapat berupa screenshot Zoom. Maksimal 5 file. Maks 10MB per file.</div>
                            <input class="form-control" type="file" id="bukti" name="bukti[]" multiple
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.webp">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-4">
                                <i class="fa-solid fa-paper-plane me-2"></i>Kirim
                            </button>
                        </div>
                    </form>

                    <script>
                        (function () {
                            var konselorEl = document.getElementById('nama_konselor');
                            var konseliEl = document.getElementById('nama_konseli');
                            var alertEl = document.getElementById('prefillAlert');

                            var tglEl = document.getElementById('tanggal_konseling');
                            var jenisEl = document.getElementById('jenis_konseling');
                            var dibahasEl = document.getElementById('hal_yang_dibahas');
                            var saranEl = document.getElementById('saran_untuk_pencaker');

                            var lastKey = '';
                            var inflight = null;

                            function setAlert(show) {
                                if (!alertEl) return;
                                alertEl.classList.toggle('d-none', !show);
                            }

                            async function tryPrefill() {
                                if (!konselorEl || !konseliEl) return;
                                var nk = (konselorEl.value || '').trim();
                                var np = (konseliEl.value || '').trim();
                                if (!nk || !np) return;

                                var key = nk + '||' + np;
                                if (key === lastKey) return;
                                lastKey = key;

                                setAlert(false);

                                try {
                                    if (inflight && typeof inflight.abort === 'function') inflight.abort();
                                } catch (e) {}

                                try {
                                    var controller = new AbortController();
                                    inflight = controller;
                                    var url = new URL("{{ route('form-hasil-konseling.prefill') }}", window.location.origin);
                                    url.searchParams.set('nama_konselor', nk);
                                    url.searchParams.set('nama_konseli', np);
                                    var res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' }, signal: controller.signal });
                                    if (!res.ok) return;
                                    var json = await res.json();
                                    if (!json || !json.found || !json.data) return;

                                    // Only fill if the user hasn't typed something different (keeps it safe).
                                    if (tglEl && (!tglEl.value || tglEl.value === '')) tglEl.value = json.data.tanggal_konseling || '';
                                    if (jenisEl && (!jenisEl.value || jenisEl.value === '')) jenisEl.value = json.data.jenis_konseling || '';
                                    if (dibahasEl && (!dibahasEl.value || dibahasEl.value.trim() === '')) dibahasEl.value = json.data.hal_yang_dibahas || '';
                                    if (saranEl && (!saranEl.value || saranEl.value.trim() === '')) saranEl.value = json.data.saran_untuk_pencaker || '';

                                    setAlert(true);
                                } catch (e) {
                                    // ignore
                                }
                            }

                            ['change', 'blur'].forEach(function (evt) {
                                if (konselorEl) konselorEl.addEventListener(evt, tryPrefill);
                                if (konseliEl) konseliEl.addEventListener(evt, tryPrefill);
                            });
                        })();
                    </script>
                </div>
            </div>
            <div class="text-muted small mt-3">
                URL form ini tidak ditampilkan di menu publik. Simpan link ini untuk akses cepat.
            </div>
        </div>
    </div>
</div>
@endsection


