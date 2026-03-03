@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Edit Thread</h1>
            <p class="text-muted mb-0">Perbarui detail diskusi sesuai kebutuhan terbaru.</p>
        </div>
        <a href="{{ route('cf.threads.show', $thread->id) }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('cf.threads.update', $thread->id) }}" class="row g-3">
                @csrf
                @method('PATCH')

                <div class="col-12">
                    <label for="cf_category_id" class="form-label">Kategori</label>
                    <select id="cf_category_id" name="cf_category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('cf_category_id', $thread->cf_category_id) === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label for="title" class="form-label">Judul Thread</label>
                    <input id="title" name="title" type="text" class="form-control" maxlength="180" value="{{ old('title', $thread->title) }}" required>
                </div>

                <div class="col-12 col-md-4">
                    <label for="author_type" class="form-label">Tipe Penulis</label>
                    <select id="author_type" name="author_type" class="form-select" required>
                        <option value="community" @selected(old('author_type', $thread->author_type) === 'community')>Komunitas</option>
                        <option value="employer" @selected(old('author_type', $thread->author_type) === 'employer')>Perusahaan</option>
                        <option value="jobseeker" @selected(old('author_type', $thread->author_type) === 'jobseeker')>Pencari Kerja</option>
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label for="location" class="form-label">Lokasi</label>
                    <input id="location" name="location" type="text" class="form-control" maxlength="120" value="{{ old('location', $thread->location) }}">
                </div>

                <div class="col-12 col-md-4">
                    <label for="sector" class="form-label">Sektor</label>
                    <input id="sector" name="sector" type="text" class="form-control" maxlength="120" value="{{ old('sector', $thread->sector) }}">
                </div>

                <div class="col-12 col-md-6">
                    <label for="job_role" class="form-label">Posisi/Jabatan</label>
                    <input id="job_role" name="job_role" type="text" class="form-control" maxlength="120" value="{{ old('job_role', $thread->job_role) }}">
                </div>

                <div class="col-12 col-md-6">
                    <label for="salary_range" class="form-label">Rentang Gaji</label>
                    <input id="salary_range" name="salary_range" type="text" class="form-control" maxlength="120" value="{{ old('salary_range', $thread->salary_range) }}">
                </div>

                <div class="col-12 col-md-4">
                    <label for="province" class="form-label">Provinsi</label>
                    <input id="province" name="province" type="text" class="form-control" maxlength="120" value="{{ old('province', $thread->province) }}">
                </div>

                <div class="col-12 col-md-4">
                    <label for="city" class="form-label">Kota/Kabupaten</label>
                    <input id="city" name="city" type="text" class="form-control" maxlength="120" value="{{ old('city', $thread->city) }}">
                </div>

                <div class="col-12 col-md-4">
                    <label for="work_type" class="form-label">Tipe Kerja</label>
                    <select id="work_type" name="work_type" class="form-select">
                        <option value="">Pilih tipe kerja</option>
                        @foreach(['Onsite', 'Hybrid', 'Remote', 'Freelance', 'Project Based'] as $type)
                            <option value="{{ $type }}" @selected(old('work_type', $thread->work_type) === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label for="experience_level" class="form-label">Level Pengalaman</label>
                    <select id="experience_level" name="experience_level" class="form-select">
                        <option value="">Pilih level</option>
                        @foreach(['Fresh Graduate', 'Junior', 'Mid', 'Senior', 'Lead'] as $level)
                            <option value="{{ $level }}" @selected(old('experience_level', $thread->experience_level) === $level)>{{ $level }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label for="body" class="form-label">Isi Diskusi</label>
                    <textarea id="body" name="body" rows="8" class="form-control" required>{{ old('body', $thread->body) }}</textarea>
                </div>

                <div class="col-12">
                    <label for="attachment_urls_text" class="form-label">Lampiran Link (opsional)</label>
                    <textarea
                        id="attachment_urls_text"
                        name="attachment_urls_text"
                        rows="4"
                        class="form-control @error('attachment_urls_text') is-invalid @enderror"
                        placeholder="Satu URL per baris, wajib HTTPS."
                    >{{ old('attachment_urls_text', implode("\n", $thread->attachment_urls ?? [])) }}</textarea>
                    <div class="form-text">Maksimum 5 link. Format aman: PDF, gambar, DOC/XLS/PPT, TXT, CSV.</div>
                    @error('attachment_urls_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 d-grid d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-success px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
