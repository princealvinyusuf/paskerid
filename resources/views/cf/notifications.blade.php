@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="cf-hero d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <span class="cf-hero-icon"><i class="fa-regular fa-bell"></i></span>
            <div class="cf-section-title mb-1">Inbox</div>
            <h1 class="h4 fw-bold mb-1">Notification Center</h1>
            <p class="text-muted mb-0">Pembaruan aktivitas diskusi komunitas Anda.</p>
        </div>
        <div class="cf-toolbar">
            <a href="{{ route('cf.index') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Forum</a>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('cf.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Tandai Semua Dibaca</button>
                </form>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h2 class="h6 fw-bold mb-0">Daftar Notifikasi</h2>
                <span class="small text-muted">Belum dibaca: {{ $unreadCount }}</span>
            </div>

            @forelse($notifications as $notification)
                <div class="p-3 border-bottom cf-list-item {{ $notification->is_read ? '' : 'bg-light' }}">
                    <div class="d-flex justify-content-between gap-3 flex-wrap">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <strong>{{ $notification->title }}</strong>
                                @if(!$notification->is_read)
                                    <span class="badge text-bg-danger">Baru</span>
                                @endif
                            </div>
                            <div class="small text-muted mb-1">
                                {{ $notification->created_at?->format('d M Y H:i') }}
                                @if($notification->actor)
                                    | Oleh: {{ $notification->actor->name }}
                                @endif
                            </div>
                            <div>{{ $notification->message }}</div>
                        </div>
                        <div class="d-flex gap-2 align-items-start">
                            <form method="POST" action="{{ route('cf.notifications.read', $notification->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    {{ $notification->is_read ? 'Lihat Detail' : 'Baca Sekarang' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4">
                    <div class="cf-empty-state">
                        <i class="fa-regular fa-bell-slash"></i>
                        <div>Belum ada notifikasi.</div>
                    </div>
                </div>
            @endforelse

            <div class="p-3">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
