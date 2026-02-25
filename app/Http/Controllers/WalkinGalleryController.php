<?php

namespace App\Http\Controllers;

use App\Models\KemitraanDetailLowongan;
use App\Models\WalkinGalleryComment;
use App\Models\WalkinGalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        $joinedCompanies = [];
        $openedPositions = [];
        $initiatorRating = null;
        $joinedCompanyRatings = [];
        $joinedCompanyParticipants = [];
        $companyPesertaHadir = null;
        $totalPeserta = null;
        $isJobPortalData = false;
        if ($company !== 'Umum') {
            $detailLowonganRows = KemitraanDetailLowongan::query()
                ->whereHas('kemitraan', function ($q) use ($company) {
                    $q->whereRaw('LOWER(institution_name) = ?', [mb_strtolower($company)]);

                    if (Schema::hasColumn('kemitraan', 'status')) {
                        $q->where('status', 'approved');
                    }
                })
                ->get([
                    'jabatan_yang_dibuka',
                    'jumlah_kebutuhan',
                    'nama_perusahaan',
                ]);

            foreach ($detailLowonganRows as $row) {
                $jabatan = trim((string) ($row->jabatan_yang_dibuka ?? ''));
                $jumlah = (int) ($row->jumlah_kebutuhan ?? 0);
                if ($jabatan !== '' && $jumlah > 0) {
                    $openedPositions[] = [
                        'jabatan_yang_dibuka' => $jabatan,
                        'jumlah_kebutuhan' => $jumlah,
                    ];
                }
            }

            $rawCompanyLists = KemitraanDetailLowongan::query()
                ->whereHas('kemitraan', function ($q) use ($company) {
                    $q->whereRaw('LOWER(institution_name) = ?', [mb_strtolower($company)])
                      ->where('tipe_penyelenggara', 'Job Portal');

                    if (Schema::hasColumn('kemitraan', 'status')) {
                        $q->where('status', 'approved');
                    }
                })
                ->pluck('nama_perusahaan')
                ->all();

            foreach ($rawCompanyLists as $rowValue) {
                $names = [];
                if (is_array($rowValue)) {
                    $names = $rowValue;
                } elseif (is_string($rowValue) && $rowValue !== '') {
                    $decoded = json_decode($rowValue, true);
                    $names = is_array($decoded) ? $decoded : [$rowValue];
                }

                foreach ($names as $name) {
                    $name = trim((string) $name);
                    if ($name !== '') {
                        $joinedCompanies[] = $name;
                    }
                }
            }

            $joinedCompanies = array_values(array_unique($joinedCompanies));
            $isJobPortalData = count($joinedCompanies) > 0;

            // Merge duplicated job titles by summing required amount.
            $openedMap = [];
            foreach ($openedPositions as $item) {
                $key = mb_strtolower($item['jabatan_yang_dibuka']);
                if (!isset($openedMap[$key])) {
                    $openedMap[$key] = $item;
                } else {
                    $openedMap[$key]['jumlah_kebutuhan'] += (int) $item['jumlah_kebutuhan'];
                }
            }
            $openedPositions = array_values($openedMap);

            if (Schema::hasTable('company_walk_in_survey') && Schema::hasTable('walk_in_survey_responses')) {
                $companyParticipantRows = DB::table('company_walk_in_survey as c')
                    ->leftJoin('walk_in_survey_responses as r', 'r.company_walk_in_survey_id', '=', 'c.id')
                    ->selectRaw('c.company_name, COUNT(r.id) AS peserta_hadir')
                    ->groupBy('c.company_name')
                    ->get();

                $companyParticipantMap = [];
                foreach ($companyParticipantRows as $row) {
                    $name = trim((string) ($row->company_name ?? ''));
                    if ($name === '') {
                        continue;
                    }
                    $companyParticipantMap[mb_strtolower($name)] = (int) ($row->peserta_hadir ?? 0);
                }

                if ($isJobPortalData) {
                    foreach ($joinedCompanies as $name) {
                        $joinedCompanyParticipants[] = [
                            'name' => $name,
                            'peserta_hadir' => $companyParticipantMap[mb_strtolower($name)] ?? 0,
                        ];
                    }
                } else {
                    $companyPesertaHadir = $companyParticipantMap[mb_strtolower($company)] ?? 0;
                }

                $companyRatingRows = DB::table('company_walk_in_survey as c')
                    ->leftJoin('walk_in_survey_responses as r', 'r.company_walk_in_survey_id', '=', 'c.id')
                    ->selectRaw('c.company_name, ROUND(AVG(r.rating_satisfaction), 2) AS avg_rating')
                    ->groupBy('c.company_name')
                    ->get();

                $companyRatingMap = [];
                foreach ($companyRatingRows as $row) {
                    $name = trim((string) ($row->company_name ?? ''));
                    if ($name === '') {
                        continue;
                    }
                    $companyRatingMap[mb_strtolower($name)] = $row->avg_rating !== null ? (float) $row->avg_rating : null;
                }

                foreach ($joinedCompanies as $name) {
                    $joinedCompanyRatings[] = [
                        'name' => $name,
                        'rating' => $companyRatingMap[mb_strtolower($name)] ?? null,
                    ];
                }

                $initiatorName = $company;
                $initiatorAvg = $companyRatingMap[mb_strtolower($company)] ?? null;
                $initiatorIdForTotal = null;
                $selectedAsInitiator = false;

                if (Schema::hasTable('walk_in_survey_initiators') && Schema::hasColumn('company_walk_in_survey', 'walk_in_initiator_id')) {
                    $initiator = DB::table('walk_in_survey_initiators')
                        ->whereRaw('LOWER(initiator_name) = ?', [mb_strtolower($company)])
                        ->first(['id', 'initiator_name']);
                    if ($initiator && isset($initiator->id)) {
                        $selectedAsInitiator = true;
                    }

                    if (!$initiator) {
                        $initiatorId = DB::table('company_walk_in_survey')
                            ->whereRaw('LOWER(company_name) = ?', [mb_strtolower($company)])
                            ->value('walk_in_initiator_id');
                        if ($initiatorId) {
                            $initiator = DB::table('walk_in_survey_initiators')
                                ->where('id', (int) $initiatorId)
                                ->first(['id', 'initiator_name']);
                        }
                    }

                    if ($initiator && isset($initiator->id)) {
                        $initiatorName = trim((string) ($initiator->initiator_name ?? $company)) ?: $company;
                        $initiatorIdForTotal = (int) $initiator->id;
                        $initiatorAvgRaw = DB::table('company_walk_in_survey as c')
                            ->leftJoin('walk_in_survey_responses as r', 'r.company_walk_in_survey_id', '=', 'c.id')
                            ->where('c.walk_in_initiator_id', (int) $initiator->id)
                            ->selectRaw('ROUND(AVG(r.rating_satisfaction), 2) AS avg_rating')
                            ->value('avg_rating');
                        $initiatorAvg = $initiatorAvgRaw !== null ? (float) $initiatorAvgRaw : null;
                    }
                }

                if ($isJobPortalData && $initiatorIdForTotal !== null) {
                    $totalPesertaRaw = DB::table('company_walk_in_survey as c')
                        ->leftJoin('walk_in_survey_responses as r', 'r.company_walk_in_survey_id', '=', 'c.id')
                        ->where('c.walk_in_initiator_id', $initiatorIdForTotal)
                        ->selectRaw('COUNT(r.id) AS total_peserta')
                        ->value('total_peserta');
                    $totalPeserta = (int) ($totalPesertaRaw ?? 0);
                }

                // If the selected gallery entity is an initiator name, use initiator-company mapping
                // from backoffice tables so participant counts match admin pages.
                if ($selectedAsInitiator && $initiatorIdForTotal !== null) {
                    $initiatorCompanyRows = DB::table('company_walk_in_survey as c')
                        ->leftJoin('walk_in_survey_responses as r', 'r.company_walk_in_survey_id', '=', 'c.id')
                        ->where('c.walk_in_initiator_id', $initiatorIdForTotal)
                        ->selectRaw('c.company_name, COUNT(r.id) AS peserta_hadir, ROUND(AVG(r.rating_satisfaction), 2) AS avg_rating')
                        ->groupBy('c.id', 'c.company_name')
                        ->orderBy('c.sort_order', 'asc')
                        ->orderBy('c.company_name', 'asc')
                        ->get();

                    $joinedCompanyParticipants = [];
                    $joinedCompanyRatings = [];
                    $computedTotal = 0;

                    foreach ($initiatorCompanyRows as $row) {
                        $name = trim((string) ($row->company_name ?? ''));
                        if ($name === '') {
                            continue;
                        }
                        $peserta = (int) ($row->peserta_hadir ?? 0);
                        $joinedCompanyParticipants[] = [
                            'name' => $name,
                            'peserta_hadir' => $peserta,
                        ];
                        $joinedCompanyRatings[] = [
                            'name' => $name,
                            'rating' => $row->avg_rating !== null ? (float) $row->avg_rating : null,
                        ];
                        $computedTotal += $peserta;
                    }

                    if (!empty($joinedCompanyParticipants)) {
                        $isJobPortalData = true;
                        $totalPeserta = $computedTotal;
                        $companyPesertaHadir = null;
                    }
                }

                $initiatorRating = [
                    'name' => $initiatorName,
                    'rating' => $initiatorAvg,
                ];
            }
        }

        return response()->json([
            'mode' => 'company',
            'company_name' => $company,
            'items' => $items,
            'comments' => $comments,
            'joined_companies' => $joinedCompanies,
            'opened_positions' => $openedPositions,
            'initiator_rating' => $initiatorRating,
            'joined_company_ratings' => $joinedCompanyRatings,
            'is_job_portal' => $isJobPortalData,
            'joined_company_participants' => $joinedCompanyParticipants,
            'company_peserta_hadir' => $companyPesertaHadir,
            'total_peserta' => $totalPeserta,
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



