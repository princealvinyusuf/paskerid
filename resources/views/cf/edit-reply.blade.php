@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Edit Balasan</h1>
            <p class="text-muted mb-0">Perbarui balasan Anda pada thread diskusi.</p>
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
            <form method="POST" action="{{ route('cf.replies.update', ['threadId' => $thread->id, 'replyId' => $reply->id]) }}">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="body" class="form-label">Isi Balasan</label>
                    <textarea id="body" name="body" rows="6" class="form-control" required>{{ old('body', $reply->body) }}</textarea>
                </div>
                <div class="d-grid d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-success px-4">Simpan Balasan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
