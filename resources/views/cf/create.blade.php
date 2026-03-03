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

                    <div class="col-12">
                        <label for="body" class="form-label">Isi Diskusi</label>
                        <textarea id="body" name="body" rows="8" class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
                        @error('body')
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
