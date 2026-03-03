<?php

namespace App\Http\Controllers;

use App\Models\CfCategory;
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
                        ->orWhere('location', 'like', '%' . $keyword . '%');
                });
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

        return view('cf.index', [
            'categories' => $categories,
            'threads' => $threads,
            'hotThreads' => $hotThreads,
            'isCfAdmin' => $this->isCfAdmin($request),
            'filters' => [
                'category' => $categorySlug,
                'author_type' => $authorType,
                'q' => $keyword,
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
        ]);

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
            'last_activity_at' => now(),
            'status' => 'open',
        ]);

        return redirect()
            ->route('cf.threads.show', $thread->id)
            ->with('success', 'Thread diskusi berhasil dibuat.');
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

        return view('cf.show', [
            'thread' => $thread,
            'isCfAdmin' => $this->isCfAdmin($request),
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

        CfReply::query()->create([
            'cf_thread_id' => (int) $thread->id,
            'user_id' => (int) $request->user()->id,
            'body' => (string) $validated['body'],
        ]);

        $thread->update(['last_activity_at' => now()]);

        return redirect()
            ->route('cf.threads.show', $thread->id)
            ->with('success', 'Balasan berhasil dikirim.');
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
}
