<?php

namespace App\Http\Controllers;

use App\Models\CfCategory;
use App\Models\CfNotification;
use App\Models\CfReply;
use App\Models\CfReport;
use App\Models\CfThread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CommunityForumController extends Controller
{
    private const SESSION_KEY = 'cf_access_granted';

    public function showGate(Request $request): View|RedirectResponse
    {
        if ((bool) $request->session()->get(self::SESSION_KEY, false)) {
            return redirect()->route('cf.index');
        }

        return view('cf.gate');
    }

    public function verifyPasscode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'passcode' => 'required|string|max:255',
        ]);

        $expectedPasscode = (string) env('CF_MENU_PASSCODE', '01062025');
        $submittedPasscode = trim((string) $validated['passcode']);

        if ($submittedPasscode !== '' && hash_equals($expectedPasscode, $submittedPasscode)) {
            $request->session()->put(self::SESSION_KEY, true);
            $request->session()->regenerate();

            return redirect()->route('cf.index');
        }

        return redirect()
            ->route('cf.gate')
            ->withErrors(['passcode' => 'Passcode tidak valid.'])
            ->withInput();
    }

    public function index(Request $request): View|RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        $categorySlug = trim((string) $request->query('category', ''));
        $authorType = trim((string) $request->query('author_type', ''));
        $keyword = trim((string) $request->query('q', ''));
        $workType = trim((string) $request->query('work_type', ''));
        $experienceLevel = trim((string) $request->query('experience_level', ''));
        $province = trim((string) $request->query('province', ''));
        $city = trim((string) $request->query('city', ''));
        $jobRole = trim((string) $request->query('job_role', ''));

        $categories = CfCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $threadsQuery = CfThread::query()
            ->with(['category:id,name,slug', 'user:id,name'])
            ->withCount('replies')
            ->when($categorySlug !== '', function ($query) use ($categorySlug) {
                $query->whereHas('category', function ($categoryQuery) use ($categorySlug) {
                    $categoryQuery->where('slug', $categorySlug);
                });
            })
            ->when($authorType !== '' && in_array($authorType, ['employer', 'jobseeker', 'community'], true), function ($query) use ($authorType) {
                $query->where('author_type', $authorType);
            })
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($nestedQuery) use ($keyword) {
                    $nestedQuery
                        ->where('title', 'like', '%' . $keyword . '%')
                        ->orWhere('body', 'like', '%' . $keyword . '%')
                        ->orWhere('sector', 'like', '%' . $keyword . '%')
                        ->orWhere('location', 'like', '%' . $keyword . '%')
                        ->orWhere('job_role', 'like', '%' . $keyword . '%')
                        ->orWhere('province', 'like', '%' . $keyword . '%')
                        ->orWhere('city', 'like', '%' . $keyword . '%')
                        ->orWhere('work_type', 'like', '%' . $keyword . '%')
                        ->orWhere('salary_range', 'like', '%' . $keyword . '%')
                        ->orWhere('experience_level', 'like', '%' . $keyword . '%');
                });
            })
            ->when($workType !== '', function ($query) use ($workType) {
                $query->where('work_type', $workType);
            })
            ->when($experienceLevel !== '', function ($query) use ($experienceLevel) {
                $query->where('experience_level', $experienceLevel);
            })
            ->when($province !== '', function ($query) use ($province) {
                $query->where('province', 'like', '%' . $province . '%');
            })
            ->when($city !== '', function ($query) use ($city) {
                $query->where('city', 'like', '%' . $city . '%');
            })
            ->when($jobRole !== '', function ($query) use ($jobRole) {
                $query->where('job_role', 'like', '%' . $jobRole . '%');
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('last_activity_at')
            ->orderByDesc('created_at');

        $threads = $threadsQuery->paginate(12)->withQueryString();

        $hotThreads = CfThread::query()
            ->with(['category:id,name,slug', 'user:id,name'])
            ->withCount('replies')
            ->orderByDesc('is_pinned')
            ->orderByDesc('replies_count')
            ->orderByDesc('views_count')
            ->orderByDesc('last_activity_at')
            ->limit(5)
            ->get();

        $unreadNotificationsCount = $request->user()
            ? (int) CfNotification::query()
                ->where('user_id', (int) $request->user()->id)
                ->where('is_read', false)
                ->count()
            : 0;

        $threadUserIds = [];
        foreach ($threads as $thread) {
            if (isset($thread->user_id)) {
                $threadUserIds[] = (int) $thread->user_id;
            }
        }
        $reputationMap = $this->buildReputationMap($threadUserIds);

        return view('cf.index', [
            'categories' => $categories,
            'threads' => $threads,
            'hotThreads' => $hotThreads,
            'isCfAdmin' => $this->isCfAdmin($request),
            'unreadNotificationsCount' => $unreadNotificationsCount,
            'reputationMap' => $reputationMap,
            'filters' => [
                'category' => $categorySlug,
                'author_type' => $authorType,
                'q' => $keyword,
                'work_type' => $workType,
                'experience_level' => $experienceLevel,
                'province' => $province,
                'city' => $city,
                'job_role' => $jobRole,
            ],
        ]);
    }

    public function createThread(Request $request): View|RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        $categories = CfCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'description']);

        return view('cf.create', [
            'categories' => $categories,
        ]);
    }

    public function storeThread(Request $request): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        if (!$request->user()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'cf_category_id' => 'required|exists:cf_categories,id',
            'title' => 'required|string|max:180',
            'body' => 'required|string|min:20|max:10000',
            'author_type' => 'required|in:employer,jobseeker,community',
            'location' => 'nullable|string|max:120',
            'sector' => 'nullable|string|max:120',
            'job_role' => 'nullable|string|max:120',
            'province' => 'nullable|string|max:120',
            'city' => 'nullable|string|max:120',
            'work_type' => 'nullable|in:Onsite,Hybrid,Remote,Freelance,Project Based',
            'salary_range' => 'nullable|string|max:120',
            'experience_level' => 'nullable|in:Fresh Graduate,Junior,Mid,Senior,Lead',
        ]);

        if ($this->isThreadSpam((int) $request->user()->id, (string) $validated['title'], (string) $validated['body'])) {
            return back()
                ->withErrors(['title' => 'Terdeteksi posting thread berulang dalam waktu singkat. Coba lagi sebentar.'])
                ->withInput();
        }

        $baseSlug = Str::slug((string) $validated['title']);
        if ($baseSlug === '') {
            $baseSlug = 'diskusi-cf';
        }

        $slug = $baseSlug;
        $counter = 1;
        while (CfThread::query()->where('slug', $slug)->exists()) {
            $counter++;
            $slug = $baseSlug . '-' . $counter;
        }

        $thread = CfThread::query()->create([
            'cf_category_id' => (int) $validated['cf_category_id'],
            'user_id' => (int) $request->user()->id,
            'title' => (string) $validated['title'],
            'slug' => $slug,
            'body' => (string) $validated['body'],
            'author_type' => (string) $validated['author_type'],
            'location' => $validated['location'] ?? null,
            'sector' => $validated['sector'] ?? null,
            'job_role' => $validated['job_role'] ?? null,
            'province' => $validated['province'] ?? null,
            'city' => $validated['city'] ?? null,
            'work_type' => $validated['work_type'] ?? null,
            'salary_range' => $validated['salary_range'] ?? null,
            'experience_level' => $validated['experience_level'] ?? null,
            'last_activity_at' => now(),
            'status' => 'open',
        ]);

        return redirect()
            ->route('cf.threads.show', $thread->id)
            ->with('success', 'Thread diskusi berhasil dibuat.');
    }

    public function editThread(Request $request, int $threadId): View|RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        $thread = CfThread::query()->findOrFail($threadId);
        if (!$this->canManageThread($request, $thread)) {
            return redirect()
                ->route('cf.threads.show', $thread->id)
                ->withErrors(['thread' => 'Anda tidak memiliki izin untuk mengubah thread ini.']);
        }

        $categories = CfCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('cf.edit-thread', [
            'thread' => $thread,
            'categories' => $categories,
        ]);
    }

    public function updateThread(Request $request, int $threadId): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        $thread = CfThread::query()->findOrFail($threadId);
        if (!$this->canManageThread($request, $thread)) {
            return redirect()
                ->route('cf.threads.show', $thread->id)
                ->withErrors(['thread' => 'Anda tidak memiliki izin untuk mengubah thread ini.']);
        }

        $validated = $request->validate([
            'cf_category_id' => 'required|exists:cf_categories,id',
            'title' => 'required|string|max:180',
            'body' => 'required|string|min:20|max:10000',
            'author_type' => 'required|in:employer,jobseeker,community',
            'location' => 'nullable|string|max:120',
            'sector' => 'nullable|string|max:120',
            'job_role' => 'nullable|string|max:120',
            'province' => 'nullable|string|max:120',
            'city' => 'nullable|string|max:120',
            'work_type' => 'nullable|in:Onsite,Hybrid,Remote,Freelance,Project Based',
            'salary_range' => 'nullable|string|max:120',
            'experience_level' => 'nullable|in:Fresh Graduate,Junior,Mid,Senior,Lead',
        ]);

        $slug = $thread->slug;
        if ((string) $thread->title !== (string) $validated['title']) {
            $baseSlug = Str::slug((string) $validated['title']);
            if ($baseSlug === '') {
                $baseSlug = 'diskusi-cf';
            }
            $slug = $baseSlug;
            $counter = 1;
            while (CfThread::query()->where('slug', $slug)->where('id', '!=', $thread->id)->exists()) {
                $counter++;
                $slug = $baseSlug . '-' . $counter;
            }
        }

        $thread->update([
            'cf_category_id' => (int) $validated['cf_category_id'],
            'title' => (string) $validated['title'],
            'slug' => $slug,
            'body' => (string) $validated['body'],
            'author_type' => (string) $validated['author_type'],
            'location' => $validated['location'] ?? null,
            'sector' => $validated['sector'] ?? null,
            'job_role' => $validated['job_role'] ?? null,
            'province' => $validated['province'] ?? null,
            'city' => $validated['city'] ?? null,
            'work_type' => $validated['work_type'] ?? null,
            'salary_range' => $validated['salary_range'] ?? null,
            'experience_level' => $validated['experience_level'] ?? null,
            'last_activity_at' => now(),
        ]);

        return redirect()
            ->route('cf.threads.show', $thread->id)
            ->with('success', 'Thread berhasil diperbarui.');
    }

    public function destroyThread(Request $request, int $threadId): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        $thread = CfThread::query()->findOrFail($threadId);
        if (!$this->canManageThread($request, $thread)) {
            return redirect()
                ->route('cf.threads.show', $thread->id)
                ->withErrors(['thread' => 'Anda tidak memiliki izin untuk menghapus thread ini.']);
        }

        $thread->delete();

        return redirect()
            ->route('cf.index')
            ->with('success', 'Thread berhasil dihapus.');
    }

    public function showThread(Request $request, int $threadId): View|RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        $thread = CfThread::query()
            ->with([
                'category:id,name,slug',
                'user:id,name',
                'replies' => function ($query) {
                    $query->with('user:id,name')->orderBy('created_at');
                },
            ])
            ->findOrFail($threadId);

        $thread->increment('views_count');
        $thread->views_count = (int) $thread->views_count + 1;

        $userIds = [(int) $thread->user_id];
        foreach ($thread->replies as $reply) {
            if (isset($reply->user_id)) {
                $userIds[] = (int) $reply->user_id;
            }
        }
        $reputationMap = $this->buildReputationMap($userIds);

        return view('cf.show', [
            'thread' => $thread,
            'isCfAdmin' => $this->isCfAdmin($request),
            'reputationMap' => $reputationMap,
        ]);
    }

    public function storeReply(Request $request, int $threadId): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        if (!$request->user()) {
            return redirect()->route('login');
        }

        $thread = CfThread::query()->findOrFail($threadId);

        if ($thread->status === 'closed') {
            return redirect()
                ->route('cf.threads.show', $thread->id)
                ->withErrors(['reply' => 'Thread ini sudah ditutup untuk balasan baru.']);
        }

        $validated = $request->validate([
            'body' => 'required|string|min:5|max:5000',
        ]);

        if ($this->isReplySpam((int) $request->user()->id, (int) $thread->id, (string) $validated['body'])) {
            return back()->withErrors(['body' => 'Terdeteksi balasan berulang dalam waktu singkat. Coba lagi sebentar.']);
        }

        $reply = CfReply::query()->create([
            'cf_thread_id' => (int) $thread->id,
            'user_id' => (int) $request->user()->id,
            'body' => (string) $validated['body'],
        ]);

        $thread->update(['last_activity_at' => now()]);
        $this->createReplyNotifications($thread, (int) $request->user()->id, (int) $reply->id);

        return redirect()
            ->route('cf.threads.show', $thread->id)
            ->with('success', 'Balasan berhasil dikirim.');
    }

    public function notifications(Request $request): View|RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        if (!$request->user()) {
            return redirect()->route('login');
        }

        $notifications = CfNotification::query()
            ->with(['actor:id,name', 'thread:id,title'])
            ->where('user_id', (int) $request->user()->id)
            ->orderBy('is_read')
            ->orderByDesc('created_at')
            ->paginate(20);

        $unreadCount = (int) CfNotification::query()
            ->where('user_id', (int) $request->user()->id)
            ->where('is_read', false)
            ->count();

        return view('cf.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markNotificationRead(Request $request, int $id): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        if (!$request->user()) {
            return redirect()->route('login');
        }

        $notification = CfNotification::query()
            ->where('user_id', (int) $request->user()->id)
            ->findOrFail($id);

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        $redirectUrl = null;
        if ($notification->cf_thread_id) {
            $redirectUrl = route('cf.threads.show', $notification->cf_thread_id);
            if ($notification->cf_reply_id) {
                $redirectUrl .= '#reply-' . $notification->cf_reply_id;
            }
        }

        if ($redirectUrl) {
            return redirect($redirectUrl);
        }

        return redirect()->route('cf.notifications.index')->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllNotificationsRead(Request $request): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        if (!$request->user()) {
            return redirect()->route('login');
        }

        CfNotification::query()
            ->where('user_id', (int) $request->user()->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return redirect()->route('cf.notifications.index')->with('success', 'Semua notifikasi telah dibaca.');
    }

    public function editReply(Request $request, int $threadId, int $replyId): View|RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        $thread = CfThread::query()->findOrFail($threadId);
        $reply = CfReply::query()
            ->where('cf_thread_id', $thread->id)
            ->findOrFail($replyId);

        if (!$this->canManageReply($request, $reply)) {
            return redirect()
                ->route('cf.threads.show', $thread->id)
                ->withErrors(['reply' => 'Anda tidak memiliki izin untuk mengubah balasan ini.']);
        }

        return view('cf.edit-reply', [
            'thread' => $thread,
            'reply' => $reply,
        ]);
    }

    public function updateReply(Request $request, int $threadId, int $replyId): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        $thread = CfThread::query()->findOrFail($threadId);
        $reply = CfReply::query()
            ->where('cf_thread_id', $thread->id)
            ->findOrFail($replyId);

        if (!$this->canManageReply($request, $reply)) {
            return redirect()
                ->route('cf.threads.show', $thread->id)
                ->withErrors(['reply' => 'Anda tidak memiliki izin untuk mengubah balasan ini.']);
        }

        $validated = $request->validate([
            'body' => 'required|string|min:5|max:5000',
        ]);

        $reply->update([
            'body' => (string) $validated['body'],
        ]);

        $thread->update(['last_activity_at' => now()]);

        return redirect()
            ->route('cf.threads.show', $thread->id)
            ->with('success', 'Balasan berhasil diperbarui.');
    }

    public function destroyReply(Request $request, int $threadId, int $replyId): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        $thread = CfThread::query()->findOrFail($threadId);
        $reply = CfReply::query()
            ->where('cf_thread_id', $thread->id)
            ->findOrFail($replyId);

        if (!$this->canManageReply($request, $reply)) {
            return redirect()
                ->route('cf.threads.show', $thread->id)
                ->withErrors(['reply' => 'Anda tidak memiliki izin untuk menghapus balasan ini.']);
        }

        $reply->delete();
        $thread->update(['last_activity_at' => now()]);

        return redirect()
            ->route('cf.threads.show', $thread->id)
            ->with('success', 'Balasan berhasil dihapus.');
    }

    public function toggleHelpfulReply(Request $request, int $threadId, int $replyId): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        if (!$request->user()) {
            return redirect()->route('login');
        }

        $thread = CfThread::query()->findOrFail($threadId);
        $reply = CfReply::query()
            ->where('cf_thread_id', $thread->id)
            ->findOrFail($replyId);

        $isThreadOwner = (int) $thread->user_id === (int) $request->user()->id;
        $isAdmin = $this->isCfAdmin($request);
        if (!$isThreadOwner && !$isAdmin) {
            return redirect()
                ->route('cf.threads.show', $thread->id)
                ->withErrors(['reply' => 'Hanya pembuat thread atau admin yang dapat menandai helpful.']);
        }

        $reply->update([
            'is_solution' => !$reply->is_solution,
        ]);

        return redirect()
            ->route('cf.threads.show', $thread->id)
            ->with('success', $reply->is_solution ? 'Balasan ditandai helpful.' : 'Tanda helpful dihapus.');
    }

    public function reportThread(Request $request, int $threadId): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        if (!$request->user()) {
            return redirect()->route('login');
        }

        $thread = CfThread::query()->findOrFail($threadId);
        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);

        CfReport::query()->create([
            'reportable_type' => 'thread',
            'reportable_id' => (int) $thread->id,
            'reported_by_user_id' => (int) $request->user()->id,
            'reason' => (string) $validated['reason'],
            'status' => 'open',
        ]);

        return redirect()
            ->route('cf.threads.show', $thread->id)
            ->with('success', 'Laporan thread diterima. Tim moderator akan meninjau.');
    }

    public function reportReply(Request $request, int $threadId, int $replyId): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        if (!$request->user()) {
            return redirect()->route('login');
        }

        $thread = CfThread::query()->findOrFail($threadId);
        $reply = CfReply::query()
            ->where('cf_thread_id', $thread->id)
            ->findOrFail($replyId);

        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);

        CfReport::query()->create([
            'reportable_type' => 'reply',
            'reportable_id' => (int) $reply->id,
            'reported_by_user_id' => (int) $request->user()->id,
            'reason' => (string) $validated['reason'],
            'status' => 'open',
        ]);

        return redirect()
            ->route('cf.threads.show', $thread->id)
            ->with('success', 'Laporan balasan diterima. Tim moderator akan meninjau.');
    }

    public function togglePin(Request $request, int $threadId): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        if (!$this->isCfAdmin($request)) {
            return redirect()
                ->route('cf.threads.show', $threadId)
                ->withErrors(['admin' => 'Anda tidak memiliki akses admin CF.']);
        }

        $thread = CfThread::query()->findOrFail($threadId);
        $thread->update(['is_pinned' => !$thread->is_pinned]);

        return redirect()
            ->route('cf.threads.show', $thread->id)
            ->with('success', $thread->is_pinned ? 'Thread dipin.' : 'Pin thread dilepas.');
    }

    public function toggleStatus(Request $request, int $threadId): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        if (!$this->isCfAdmin($request)) {
            return redirect()
                ->route('cf.threads.show', $threadId)
                ->withErrors(['admin' => 'Anda tidak memiliki akses admin CF.']);
        }

        $thread = CfThread::query()->findOrFail($threadId);
        $nextStatus = $thread->status === 'open' ? 'closed' : 'open';
        $thread->update(['status' => $nextStatus]);

        return redirect()
            ->route('cf.threads.show', $thread->id)
            ->with('success', $nextStatus === 'closed' ? 'Thread ditutup.' : 'Thread dibuka kembali.');
    }

    public function reports(Request $request): View|RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        if (!$this->isCfAdmin($request)) {
            return redirect()
                ->route('cf.index')
                ->withErrors(['admin' => 'Anda tidak memiliki akses admin CF.']);
        }

        $status = trim((string) $request->query('status', ''));
        $allowedStatus = ['open', 'resolved', 'rejected'];

        $reports = CfReport::query()
            ->with(['reporter:id,name,email', 'reviewer:id,name,email'])
            ->when(in_array($status, $allowedStatus, true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('status')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $threadIds = [];
        $replyIds = [];
        foreach ($reports as $report) {
            if ($report->reportable_type === 'thread') {
                $threadIds[] = (int) $report->reportable_id;
            } elseif ($report->reportable_type === 'reply') {
                $replyIds[] = (int) $report->reportable_id;
            }
        }

        $threads = CfThread::query()
            ->whereIn('id', array_values(array_unique($threadIds)))
            ->get(['id', 'title'])
            ->keyBy('id');

        $replies = CfReply::query()
            ->whereIn('id', array_values(array_unique($replyIds)))
            ->get(['id', 'cf_thread_id', 'body'])
            ->keyBy('id');

        $targetMap = [];
        foreach ($reports as $report) {
            if ($report->reportable_type === 'thread') {
                $thread = $threads->get((int) $report->reportable_id);
                $targetMap[(int) $report->id] = [
                    'exists' => (bool) $thread,
                    'label' => $thread ? ('Thread: ' . $thread->title) : 'Thread tidak ditemukan',
                    'url' => $thread ? route('cf.threads.show', $thread->id) : null,
                ];
                continue;
            }

            $reply = $replies->get((int) $report->reportable_id);
            $threadId = $reply ? (int) $reply->cf_thread_id : null;
            $preview = $reply ? mb_substr((string) $reply->body, 0, 80) : null;
            $targetMap[(int) $report->id] = [
                'exists' => (bool) $reply,
                'label' => $reply ? ('Reply: ' . $preview . (mb_strlen((string) $reply->body) > 80 ? '...' : '')) : 'Reply tidak ditemukan',
                'url' => $reply ? route('cf.threads.show', $threadId) . '#reply-' . $reply->id : null,
            ];
        }

        $statusCounts = [
            'open' => (int) CfReport::query()->where('status', 'open')->count(),
            'resolved' => (int) CfReport::query()->where('status', 'resolved')->count(),
            'rejected' => (int) CfReport::query()->where('status', 'rejected')->count(),
        ];

        return view('cf.reports', [
            'reports' => $reports,
            'targetMap' => $targetMap,
            'status' => $status,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function updateReportStatus(Request $request, int $id): RedirectResponse
    {
        $accessRedirect = $this->ensureAccess($request);
        if ($accessRedirect) {
            return $accessRedirect;
        }

        if (!$this->isCfAdmin($request)) {
            return redirect()
                ->route('cf.index')
                ->withErrors(['admin' => 'Anda tidak memiliki akses admin CF.']);
        }

        $validated = $request->validate([
            'status' => 'required|in:open,resolved,rejected',
            'review_note' => 'nullable|string|max:1000',
        ]);

        $report = CfReport::query()->findOrFail($id);
        $nextStatus = (string) $validated['status'];
        $note = trim((string) ($validated['review_note'] ?? ''));

        if ($nextStatus === 'open') {
            $report->update([
                'status' => 'open',
                'reviewed_by_user_id' => null,
                'review_note' => $note !== '' ? $note : null,
                'reviewed_at' => null,
            ]);
        } else {
            $report->update([
                'status' => $nextStatus,
                'reviewed_by_user_id' => (int) $request->user()->id,
                'review_note' => $note !== '' ? $note : null,
                'reviewed_at' => now(),
            ]);
        }

        return redirect()
            ->route('cf.admin.reports.index', ['status' => $request->query('status', '')])
            ->with('success', 'Status laporan berhasil diperbarui.');
    }

    private function ensureAccess(Request $request): ?RedirectResponse
    {
        if ((bool) $request->session()->get(self::SESSION_KEY, false)) {
            return null;
        }

        return redirect()
            ->route('cf.gate')
            ->withErrors(['passcode' => 'Masukkan passcode untuk mengakses menu CF.']);
    }

    private function isCfAdmin(Request $request): bool
    {
        $user = $request->user();
        if (!$user || !isset($user->email)) {
            return false;
        }

        $adminEmailsRaw = (string) env('CF_ADMIN_EMAILS', '');
        if ($adminEmailsRaw === '') {
            return false;
        }

        $adminEmails = array_filter(array_map('trim', explode(',', $adminEmailsRaw)));
        return in_array(strtolower((string) $user->email), array_map('strtolower', $adminEmails), true);
    }

    private function canManageThread(Request $request, CfThread $thread): bool
    {
        $user = $request->user();
        if (!$user) {
            return false;
        }

        return (int) $thread->user_id === (int) $user->id || $this->isCfAdmin($request);
    }

    private function canManageReply(Request $request, CfReply $reply): bool
    {
        $user = $request->user();
        if (!$user) {
            return false;
        }

        return (int) $reply->user_id === (int) $user->id || $this->isCfAdmin($request);
    }

    private function isThreadSpam(int $userId, string $title, string $body): bool
    {
        $normalizedTitle = mb_strtolower(trim($title));
        $normalizedBody = mb_strtolower(trim($body));

        return CfThread::query()
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subMinutes(3))
            ->where(function ($query) use ($normalizedTitle, $normalizedBody) {
                $query
                    ->whereRaw('LOWER(TRIM(title)) = ?', [$normalizedTitle])
                    ->orWhereRaw('LOWER(TRIM(body)) = ?', [$normalizedBody]);
            })
            ->exists();
    }

    private function isReplySpam(int $userId, int $threadId, string $body): bool
    {
        $normalizedBody = mb_strtolower(trim($body));

        $duplicateRecentReply = CfReply::query()
            ->where('user_id', $userId)
            ->where('cf_thread_id', $threadId)
            ->where('created_at', '>=', now()->subMinutes(2))
            ->whereRaw('LOWER(TRIM(body)) = ?', [$normalizedBody])
            ->exists();

        if ($duplicateRecentReply) {
            return true;
        }

        $burstCount = CfReply::query()
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subMinute())
            ->count();

        return $burstCount >= 5;
    }

    private function createReplyNotifications(CfThread $thread, int $actorUserId, int $replyId): void
    {
        $recipientIds = [];

        if ((int) $thread->user_id !== $actorUserId) {
            $recipientIds[] = (int) $thread->user_id;
        }

        $participantIds = CfReply::query()
            ->where('cf_thread_id', (int) $thread->id)
            ->where('user_id', '!=', $actorUserId)
            ->distinct()
            ->pluck('user_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $recipientIds = array_values(array_unique(array_merge($recipientIds, $participantIds)));
        if (empty($recipientIds)) {
            return;
        }

        $actorName = (string) \App\Models\User::query()
            ->where('id', $actorUserId)
            ->value('name');
        $actorLabel = trim($actorName) !== '' ? $actorName : 'Seseorang';
        $title = 'Balasan baru pada thread CF';
        $message = $actorLabel . ' membalas thread "' . $thread->title . '".';

        $rows = [];
        foreach ($recipientIds as $recipientId) {
            $rows[] = [
                'user_id' => $recipientId,
                'type' => 'thread_reply',
                'cf_thread_id' => (int) $thread->id,
                'cf_reply_id' => $replyId,
                'actor_user_id' => $actorUserId,
                'title' => $title,
                'message' => $message,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        CfNotification::query()->insert($rows);
    }

    private function buildReputationMap(array $userIds): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $userIds), fn ($id) => $id > 0)));
        if (empty($ids)) {
            return [];
        }

        $threadCounts = CfThread::query()
            ->selectRaw('user_id, COUNT(*) as total')
            ->whereIn('user_id', $ids)
            ->groupBy('user_id')
            ->pluck('total', 'user_id')
            ->all();

        $replyCounts = CfReply::query()
            ->selectRaw('user_id, COUNT(*) as total')
            ->whereIn('user_id', $ids)
            ->groupBy('user_id')
            ->pluck('total', 'user_id')
            ->all();

        $helpfulCounts = CfReply::query()
            ->selectRaw('user_id, COUNT(*) as total')
            ->whereIn('user_id', $ids)
            ->where('is_solution', true)
            ->groupBy('user_id')
            ->pluck('total', 'user_id')
            ->all();

        $map = [];
        foreach ($ids as $id) {
            $threadTotal = (int) ($threadCounts[$id] ?? 0);
            $replyTotal = (int) ($replyCounts[$id] ?? 0);
            $helpfulTotal = (int) ($helpfulCounts[$id] ?? 0);
            $score = ($threadTotal * 5) + ($replyTotal * 2) + ($helpfulTotal * 10);

            $map[$id] = [
                'threads' => $threadTotal,
                'replies' => $replyTotal,
                'helpful' => $helpfulTotal,
                'score' => $score,
                'badge' => $this->resolveReputationBadge($score, $helpfulTotal),
            ];
        }

        return $map;
    }

    private function resolveReputationBadge(int $score, int $helpfulTotal): string
    {
        if ($score >= 120 || $helpfulTotal >= 10) {
            return 'Kontributor Tepercaya';
        }

        if ($score >= 40 || $helpfulTotal >= 3) {
            return 'Kontributor Aktif';
        }

        if ($score > 0) {
            return 'Kontributor Baru';
        }

        return 'Pengguna Baru';
    }
}
