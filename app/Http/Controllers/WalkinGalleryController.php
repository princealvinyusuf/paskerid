<?php

namespace App\Http\Controllers;

use App\Models\WalkinGalleryComment;
use App\Models\WalkinGalleryItem;
use Illuminate\Http\Request;

class WalkinGalleryController extends Controller
{
    public function feed(Request $request)
    {
        $type = $request->query('type'); // all|photo|video|comment (UI convenience)

        $itemQuery = WalkinGalleryItem::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderByDesc('created_at');

        if ($type === 'photo') {
            $itemQuery->where('type', 'photo');
        } elseif ($type === 'video') {
            $itemQuery->whereIn('type', ['video_upload', 'video_embed']);
        } elseif (is_string($type) && in_array($type, ['video_upload', 'video_embed'], true)) {
            $itemQuery->where('type', $type);
        }

        $items = $itemQuery->limit(60)->get([
            'id',
            'type',
            'title',
            'caption',
            'media_path',
            'thumbnail_path',
            'embed_url',
            'embed_thumbnail_url',
            'created_at',
        ]);

        $comments = WalkinGalleryComment::query()
            ->where('status', 'approved')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get([
                'id',
                'walkin_gallery_item_id',
                'name',
                'comment',
                'created_at',
            ]);

        return response()->json([
            'items' => $items,
            'comments' => $comments,
        ]);
    }

    public function storeComment(Request $request)
    {
        // Honeypot: bots tend to fill this
        if ($request->filled('website')) {
            return response()->json(['ok' => true], 200);
        }

        $validated = $request->validate([
            'walkin_gallery_item_id' => 'nullable|integer|exists:walkin_gallery_items,id',
            'name' => 'required|string|max:80',
            'comment' => 'required|string|max:1000',
            'website' => 'nullable|string|max:0',
        ]);

        $comment = WalkinGalleryComment::create([
            'walkin_gallery_item_id' => $validated['walkin_gallery_item_id'] ?? null,
            'name' => trim($validated['name']),
            'comment' => trim($validated['comment']),
            'status' => 'pending',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Komentar kamu akan tampil setelah disetujui admin.',
            'id' => $comment->id,
        ]);
    }
}


