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

    <div class="cf-hero d-flex justify-content-between align-items-start mb-3 gap-2">
        <div>
            <span class="cf-hero-icon"><i class="fa-solid fa-message"></i></span>
            <a href="{{ route('cf.index') }}" class="small text-decoration-none">&larr; Kembali ke forum</a>
            <span class="mx-1 text-muted">|</span>
            <a href="{{ route('cf.guidelines') }}" class="small text-decoration-none">Panduan Komunitas</a>
            @auth
                <span class="mx-1 text-muted">|</span>
                <a href="{{ route('cf.notifications.index') }}" class="small text-decoration-none">Notifikasi</a>
            @endauth
            @if($isCfAdmin ?? false)
                <span class="mx-1 text-muted">|</span>
                <a href="{{ route('cf.admin.reports.index') }}" class="small text-decoration-none">Moderation Center</a>
                <span class="mx-1 text-muted">|</span>
                <a href="{{ route('cf.admin.verifications.index') }}" class="small text-decoration-none">Verification Admin</a>
            @endif
            <h1 class="h4 fw-bold mt-2 mb-1">{{ $thread->title }}</h1>
            @php $threadAuthorRep = $reputationMap[$thread->user_id] ?? null; @endphp
            @php $threadAuthorVerification = $verificationMap[$thread->user_id] ?? null; @endphp
            <div class="small text-muted">
                {{ $thread->category->name ?? '-' }} |
                Oleh {{ $thread->user->name ?? 'Anonim' }} |
                @if($threadAuthorRep)
                    <span class="badge text-bg-light border">{{ $threadAuthorRep['badge'] }}</span>
                    <span>Skor: {{ $threadAuthorRep['score'] }}</span> |
                @endif
                @if(!empty($threadAuthorVerification['label']))
                    <span class="badge text-bg-primary">{{ $threadAuthorVerification['label'] }}</span> |
                @endif
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

    @if($thread->is_hidden)
        <div class="alert alert-warning">
            Thread ini sedang disembunyikan sementara untuk review moderator.
            @if($thread->hidden_reason)
                <div class="small mt-1">{{ $thread->hidden_reason }}</div>
            @endif
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            @if($thread->location || $thread->sector || $thread->job_role || $thread->work_type || $thread->experience_level || $thread->province || $thread->city || $thread->salary_range)
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
                    @if($thread->job_role)
                        | Jabatan: {{ $thread->job_role }}
                    @endif
                    @if($thread->work_type)
                        | Tipe Kerja: {{ $thread->work_type }}
                    @endif
                    @if($thread->experience_level)
                        | Pengalaman: {{ $thread->experience_level }}
                    @endif
                    @if($thread->province || $thread->city)
                        | Area:
                        @if($thread->city){{ $thread->city }}@endif
                        @if($thread->city && $thread->province), @endif
                        @if($thread->province){{ $thread->province }}@endif
                    @endif
                    @if($thread->salary_range)
                        | Gaji: {{ $thread->salary_range }}
                    @endif
                </div>
            @endif
            <div style="white-space: pre-line;">{{ $thread->body }}</div>

            @if(!empty($thread->attachment_urls))
                <hr>
                <div>
                    <strong>Lampiran:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach(($thread->attachment_urls ?? []) as $url)
                            @php $fileLabel = basename((string) parse_url($url, PHP_URL_PATH)); @endphp
                            <li>
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">
                                    {{ $fileLabel !== '' ? $fileLabel : $url }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @auth
                @if((int) auth()->id() === (int) $thread->user_id || ($isCfAdmin ?? false))
                    <hr>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('cf.threads.edit', $thread->id) }}" class="btn btn-outline-primary btn-sm">Edit Thread</a>
                        <form method="POST" action="{{ route('cf.threads.destroy', $thread->id) }}" onsubmit="return confirm('Hapus thread ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Hapus Thread</button>
                        </form>
                    </div>
                @endif

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

    @if(!empty($integrationLinks ?? []))
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="h6 fw-bold mb-0"><i class="fa-solid fa-diagram-project me-1 text-primary"></i>Program Terkait PaskerID</h3>
                    <span class="small text-muted">Integrasi fase growth</span>
                </div>
                <div class="row g-2">
                    @foreach($integrationLinks as $item)
                        <div class="col-12 col-md-6">
                            <a href="{{ $item['url'] }}" class="text-decoration-none">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-dark">{{ $item['title'] }}</strong>
                                        <span class="badge text-bg-light border text-dark">{{ $item['badge'] }}</span>
                                    </div>
                                    <div class="small text-muted">{{ $item['description'] }}</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="h5 fw-bold mb-0"><i class="fa-regular fa-comments me-1 text-primary"></i>Balasan ({{ $thread->replies->count() }})</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-0">
            @forelse($thread->replies as $reply)
                <div class="p-3 border-bottom" id="reply-{{ $reply->id }}">
                    @php $replyAuthorRep = $reputationMap[$reply->user_id] ?? null; @endphp
                    @php $replyAuthorVerification = $verificationMap[$reply->user_id] ?? null; @endphp
                    <div class="small text-muted mb-1">
                        {{ $reply->user->name ?? 'Anonim' }}
                        @if($replyAuthorRep)
                            <span class="badge text-bg-light border">{{ $replyAuthorRep['badge'] }}</span>
                        @endif
                        @if(!empty($replyAuthorVerification['label']))
                            <span class="badge text-bg-primary">{{ $replyAuthorVerification['label'] }}</span>
                        @endif
                        | {{ $reply->created_at?->format('d M Y H:i') }}
                    </div>
                    <div class="cf-soft p-2 mt-2" style="white-space: pre-line;">{{ $reply->body }}</div>
                    @if($reply->is_hidden)
                        <div class="mt-2">
                            <span class="badge text-bg-warning">Hidden Pending Review</span>
                            @if($reply->hidden_reason)
                                <div class="small text-muted mt-1">{{ $reply->hidden_reason }}</div>
                            @endif
                        </div>
                    @endif
                    @if($reply->is_solution)
                        <div class="mt-2">
                            <span class="badge text-bg-success">Helpful</span>
                        </div>
                    @endif
                    @auth
                        @if((int) auth()->id() === (int) $thread->user_id || ($isCfAdmin ?? false))
                            <form method="POST" action="{{ route('cf.replies.toggle-helpful', ['threadId' => $thread->id, 'replyId' => $reply->id]) }}" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">
                                    {{ $reply->is_solution ? 'Batalkan Helpful' : 'Tandai Helpful' }}
                                </button>
                            </form>
                        @endif

                        @if((int) auth()->id() === (int) $reply->user_id || ($isCfAdmin ?? false))
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <a href="{{ route('cf.replies.edit', ['threadId' => $thread->id, 'replyId' => $reply->id]) }}" class="btn btn-outline-primary btn-sm">
                                    Edit Balasan
                                </a>
                                <form method="POST" action="{{ route('cf.replies.destroy', ['threadId' => $thread->id, 'replyId' => $reply->id]) }}" onsubmit="return confirm('Hapus balasan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus Balasan</button>
                                </form>
                            </div>
                        @endif

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
                <div class="p-4">
                    <div class="cf-empty-state">
                        <i class="fa-regular fa-message"></i>
                        <div>Belum ada balasan.</div>
                    </div>
                </div>
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
