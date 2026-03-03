@extends('layouts.app')

@section('content')
<div class="container py-5">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-start mb-3 gap-2">
        <div>
            <a href="{{ route('cf.index') }}" class="small text-decoration-none">&larr; Kembali ke forum</a>
            @if($isCfAdmin ?? false)
                <span class="mx-1 text-muted">|</span>
                <a href="{{ route('cf.admin.reports.index') }}" class="small text-decoration-none">Moderation Center</a>
            @endif
            <h1 class="h4 fw-bold mt-2 mb-1">{{ $thread->title }}</h1>
            <div class="small text-muted">
                {{ $thread->category->name ?? '-' }} |
                Oleh {{ $thread->user->name ?? 'Anonim' }} |
                @if($thread->author_type === 'employer')
                    Perusahaan
                @elseif($thread->author_type === 'jobseeker')
                    Pencari Kerja
                @else
                    Komunitas
                @endif
                |
                {{ $thread->created_at?->format('d M Y H:i') }} |
                Views: {{ number_format($thread->views_count) }}
            </div>
        </div>
        <div class="d-flex gap-2">
            @if($thread->is_pinned)
                <span class="badge text-bg-info">Pinned</span>
            @endif
            @if($thread->status === 'closed')
                <span class="badge text-bg-secondary">Thread Closed</span>
            @endif
        </div>
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

            @auth
                <hr>
                <form method="POST" action="{{ route('cf.threads.report', $thread->id) }}">
                    @csrf
                    <label for="thread-report-reason" class="form-label small fw-semibold mb-1">Laporkan thread ini</label>
                    <div class="d-flex gap-2">
                        <input id="thread-report-reason" name="reason" type="text" class="form-control form-control-sm" placeholder="Contoh: spam atau konten tidak relevan" required>
                        <button type="submit" class="btn btn-outline-danger btn-sm">Laporkan</button>
                    </div>
                </form>
            @endauth

            @if($isCfAdmin ?? false)
                <hr>
                <div class="d-flex gap-2 flex-wrap">
                    <form method="POST" action="{{ route('cf.threads.toggle-pin', $thread->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            {{ $thread->is_pinned ? 'Lepas Pin' : 'Pin Thread' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('cf.threads.toggle-status', $thread->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            {{ $thread->status === 'open' ? 'Tutup Thread' : 'Buka Thread' }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="h5 fw-bold mb-0">Balasan ({{ $thread->replies->count() }})</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-0">
            @forelse($thread->replies as $reply)
                <div class="p-3 border-bottom" id="reply-{{ $reply->id }}">
                    <div class="small text-muted mb-1">
                        {{ $reply->user->name ?? 'Anonim' }} | {{ $reply->created_at?->format('d M Y H:i') }}
                    </div>
                    <div style="white-space: pre-line;">{{ $reply->body }}</div>
                    @auth
                        <form method="POST" action="{{ route('cf.replies.report', ['threadId' => $thread->id, 'replyId' => $reply->id]) }}" class="mt-2">
                            @csrf
                            <div class="d-flex gap-2">
                                <input name="reason" type="text" class="form-control form-control-sm" placeholder="Laporkan balasan ini" required>
                                <button type="submit" class="btn btn-outline-danger btn-sm">Laporkan</button>
                            </div>
                        </form>
                    @endauth
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
