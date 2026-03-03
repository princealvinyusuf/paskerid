@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Buat Thread Baru</h1>
            <p class="text-muted mb-0">Mulai diskusi seputar kebutuhan talenta dan peluang kerja.</p>
        </div>
        <a href="{{ route('cf.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>

    @guest
        <div class="alert alert-warning">
            Silakan <a href="{{ route('login') }}">login</a> untuk membuat thread.
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('cf.threads.store') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label for="cf_category_id" class="form-label">Kategori</label>
                        <select id="cf_category_id" name="cf_category_id" class="form-select @error('cf_category_id') is-invalid @enderror" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('cf_category_id') === (string) $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('cf_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="title" class="form-label">Judul Thread</label>
                        <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror" maxlength="180" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="author_type" class="form-label">Tipe Penulis</label>
                        <select id="author_type" name="author_type" class="form-select @error('author_type') is-invalid @enderror" required>
                            <option value="community" @selected(old('author_type') === 'community')>Komunitas</option>
                            <option value="employer" @selected(old('author_type') === 'employer')>Perusahaan</option>
                            <option value="jobseeker" @selected(old('author_type') === 'jobseeker')>Pencari Kerja</option>
                        </select>
                        @error('author_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="location" class="form-label">Lokasi (opsional)</label>
                        <input id="location" name="location" type="text" class="form-control @error('location') is-invalid @enderror" maxlength="120" value="{{ old('location') }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="sector" class="form-label">Sektor (opsional)</label>
                        <input id="sector" name="sector" type="text" class="form-control @error('sector') is-invalid @enderror" maxlength="120" value="{{ old('sector') }}">
                        @error('sector')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="job_role" class="form-label">Posisi/Jabatan (opsional)</label>
                        <input id="job_role" name="job_role" type="text" class="form-control @error('job_role') is-invalid @enderror" maxlength="120" value="{{ old('job_role') }}" placeholder="Contoh: Sales Executive, UI/UX Designer">
                        @error('job_role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="salary_range" class="form-label">Rentang Gaji (opsional)</label>
                        <input id="salary_range" name="salary_range" type="text" class="form-control @error('salary_range') is-invalid @enderror" maxlength="120" value="{{ old('salary_range') }}" placeholder="Contoh: 5 - 8 juta / nego">
                        @error('salary_range')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="province" class="form-label">Provinsi (opsional)</label>
                        <input id="province" name="province" type="text" class="form-control @error('province') is-invalid @enderror" maxlength="120" value="{{ old('province') }}">
                        @error('province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="city" class="form-label">Kota/Kabupaten (opsional)</label>
                        <input id="city" name="city" type="text" class="form-control @error('city') is-invalid @enderror" maxlength="120" value="{{ old('city') }}">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="work_type" class="form-label">Tipe Kerja (opsional)</label>
                        <select id="work_type" name="work_type" class="form-select @error('work_type') is-invalid @enderror">
                            <option value="">Pilih tipe kerja</option>
                            @foreach(['Onsite', 'Hybrid', 'Remote', 'Freelance', 'Project Based'] as $type)
                                <option value="{{ $type }}" @selected(old('work_type') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('work_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="experience_level" class="form-label">Level Pengalaman (opsional)</label>
                        <select id="experience_level" name="experience_level" class="form-select @error('experience_level') is-invalid @enderror">
                            <option value="">Pilih level</option>
                            @foreach(['Fresh Graduate', 'Junior', 'Mid', 'Senior', 'Lead'] as $level)
                                <option value="{{ $level }}" @selected(old('experience_level') === $level)>{{ $level }}</option>
                            @endforeach
                        </select>
                        @error('experience_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="body" class="form-label">Isi Diskusi</label>
                        <textarea id="body" name="body" rows="8" class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="attachment_urls_text" class="form-label">Lampiran Link (opsional)</label>
                        <textarea
                            id="attachment_urls_text"
                            name="attachment_urls_text"
                            rows="4"
                            class="form-control @error('attachment_urls_text') is-invalid @enderror"
                            placeholder="Satu URL per baris, wajib HTTPS. Contoh: https://example.com/file.pdf"
                        >{{ old('attachment_urls_text') }}</textarea>
                        <div class="form-text">Maksimum 5 link. Format aman: PDF, gambar, DOC/XLS/PPT, TXT, CSV.</div>
                        @error('attachment_urls_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 d-grid d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-success px-4">Publikasikan Thread</button>
                    </div>
                </form>
            </div>
        </div>
    @endguest
</div>
@endsection
