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
        $company = trim((string) $request->query('company', ''));

        // If no company specified: return company cards
        if ($company === '') {
            $items = WalkinGalleryItem::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->limit(300)
                ->get([
                    'id',
                    'type',
                    'company_name',
                    'title',
                    'caption',
                    'media_path',
                    'thumbnail_path',
                    'embed_thumbnail_url',
                    'created_at',
                ]);

            $companiesMap = [];
            foreach ($items as $it) {
                $name = trim((string) ($it->company_name ?? ''));
                if ($name === '') { $name = 'Umum'; }
                if (!isset($companiesMap[$name])) {
                    $companiesMap[$name] = [
                        'company_name' => $name,
                        'count' => 0,
                        'cover_type' => null,
                        'cover_media_path' => null,
                        'cover_thumbnail_path' => null,
                        'cover_embed_thumbnail_url' => null,
                        'latest_at' => null,
                    ];
                }
                $companiesMap[$name]['count']++;
                $companiesMap[$name]['latest_at'] = $companiesMap[$name]['latest_at'] ?: $it->created_at;

                // pick first good cover
                if ($companiesMap[$name]['cover_type'] === null) {
                    $companiesMap[$name]['cover_type'] = $it->type;
                    $companiesMap[$name]['cover_media_path'] = $it->media_path;
                    $companiesMap[$name]['cover_thumbnail_path'] = $it->thumbnail_path;
                    $companiesMap[$name]['cover_embed_thumbnail_url'] = $it->embed_thumbnail_url;
                }
            }

            $companies = array_values($companiesMap);

            usort($companies, function ($a, $b) {
                // sort by count desc then name asc
                if (($b['count'] ?? 0) !== ($a['count'] ?? 0)) {
                    return ($b['count'] ?? 0) <=> ($a['count'] ?? 0);
                }
                return strcmp((string) $a['company_name'], (string) $b['company_name']);
            });

            return response()->json([
                'mode' => 'companies',
                'companies' => $companies,
            ]);
        }

        $itemQuery = WalkinGalleryItem::query()
            ->where('is_published', true)
            ->where(function ($q) use ($company) {
                $q->where('company_name', $company);
                if ($company === 'Umum') {
                    $q->orWhereNull('company_name')->orWhere('company_name', '');
                }
            })
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
            'company_name',
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
            ->where(function ($q) use ($company) {
                $q->where('company_name', $company);
                if ($company === 'Umum') {
                    $q->orWhereNull('company_name')->orWhere('company_name', '');
                }
            })
            ->orderByDesc('created_at')
            ->limit(50)
            ->get([
                'id',
                'walkin_gallery_item_id',
                'company_name',
                'name',
                'comment',
                'created_at',
            ]);

        return response()->json([
            'mode' => 'company',
            'company_name' => $company,
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
            'company_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:80',
            'comment' => 'required|string|max:1000',
            'website' => 'nullable|string|max:0',
        ]);

        $comment = WalkinGalleryComment::create([
            'walkin_gallery_item_id' => $validated['walkin_gallery_item_id'] ?? null,
            'company_name' => isset($validated['company_name']) ? trim((string) $validated['company_name']) : null,
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



