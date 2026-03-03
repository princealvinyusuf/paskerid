@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-start mb-3 gap-2">
        <div>
            <a href="{{ route('cf.index') }}" class="small text-decoration-none">&larr; Kembali ke forum</a>
            <h1 class="h4 fw-bold mt-2 mb-1">{{ $thread->title }}</h1>
            <div class="small text-muted">
                {{ $thread->category->name ?? '-' }} |
                Oleh {{ $thread->user->name ?? 'Anonim' }} |
                {{ strtoupper($thread->author_type) }} |
                {{ $thread->created_at?->format('d M Y H:i') }} |
                Views: {{ number_format($thread->views_count) }}
            </div>
        </div>
        @if($thread->status === 'closed')
            <span class="badge text-bg-secondary">Thread Closed</span>
        @endif
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            @if($thread->location || $thread->sector)
                <div class="small text-muted mb-2">
                    @if($thread->location)
                        Lokasi: {{ $thread->location }}
                    @endif
                    @if($thread->location && $thread->sector)
                        |
                    @endif
                    @if($thread->sector)
                        Sektor: {{ $thread->sector }}
                    @endif
                </div>
            @endif
            <div style="white-space: pre-line;">{{ $thread->body }}</div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="h5 fw-bold mb-0">Balasan ({{ $thread->replies->count() }})</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-0">
            @forelse($thread->replies as $reply)
                <div class="p-3 border-bottom">
                    <div class="small text-muted mb-1">
                        {{ $reply->user->name ?? 'Anonim' }} | {{ $reply->created_at?->format('d M Y H:i') }}
                    </div>
                    <div style="white-space: pre-line;">{{ $reply->body }}</div>
                </div>
            @empty
                <div class="p-4 text-center text-muted">Belum ada balasan.</div>
            @endforelse
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h3 class="h6 fw-bold">Tulis Balasan</h3>
            @guest
                <div class="alert alert-warning mb-0">
                    Silakan <a href="{{ route('login') }}">login</a> untuk membalas thread.
                </div>
            @else
                @if($thread->status === 'closed')
                    <div class="alert alert-secondary mb-0">
                        Thread ini sudah ditutup untuk balasan baru.
                    </div>
                @else
                    <form method="POST" action="{{ route('cf.replies.store', $thread->id) }}">
                        @csrf
                        <div class="mb-3">
                            <textarea
                                name="body"
                                rows="4"
                                class="form-control @error('body') is-invalid @enderror @error('reply') is-invalid @enderror"
                                placeholder="Tulis balasan Anda..."
                                required
                            >{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('reply')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Kirim Balasan</button>
                    </form>
                @endif
            @endguest
        </div>
    </div>
</div>
@endsection
