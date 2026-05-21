<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Statistic;
use App\Models\Information;
use App\Models\Chart;
use App\Models\TopList;
use App\Models\Contribution;
use App\Models\Service;
use App\Models\News;
use App\Models\Testimonial;
use App\Models\HeroStatistic;
use App\Models\JobCharacteristic;
use App\Models\JobCharacteristic2;
use App\Models\JobCharacteristic3;
use App\Models\TopicData;
use App\Models\Dataset;
use App\Models\HighlightStatistic;
use App\Models\HighlightStatisticSecondary;
use App\Models\Visitor;
use App\Models\MitraKerja;
use App\Models\KarirhubAds;
use App\Models\HomePopupSetting;
use App\Models\HomePopupItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        $statistics = Statistic::orderBy('order')->get();
        $heroStatistics = HeroStatistic::orderBy('order')->get();
        $statistik = Information::where('type', 'statistik')->orderByDesc('date')->take(5)->get();
        $publikasi = Information::where('type', 'publikasi')->where('status', 'publik')->orderByDesc('date')->take(5)->get();
        $charts = Chart::orderBy('order')->get();
        $typeMeta = [
            'skills' => [
                'title' => 'Top 5 Kualifikasi Paling Umum Pencari Kerja',
                'desc' => 'Menampilkan kualifikasi yang paling banyak dicari oleh pencari kerja.',
                'icon' => 'fa-user-graduate',
            ],
            'provinces' => [
                'title' => 'Top 5 Provinsi dengan Pencari Kerja Terbanyak',
                'desc' => 'Memetakan konsentrasi pencari kerja secara geografis.',
                'icon' => 'fa-map-marked-alt',
            ],
            'talents' => [
                'title' => 'Top 5 Talenta dengan Lowongan Terbanyak',
                'desc' => 'Talenta yang paling banyak dibutuhkan di pasar kerja.',
                'icon' => 'fa-users',
            ],
        ];
        $topLists = TopList::all()->map(function($list) use ($typeMeta) {
            $meta = $typeMeta[$list->type] ?? [];
            $list->meta = $meta;
            $list->items = json_decode($list->data_json, true)['items'] ?? [];
            return $list;
        });
        $contributions = Contribution::all();
        $services = Service::all();
        $news = News::orderByDesc('date')->take(3)->get();
        $testimonials = Testimonial::all();
        $jobCharacteristics = JobCharacteristic::orderBy('order')->take(4)->get();
        $jobCharacteristics2 = JobCharacteristic2::orderBy('order')->take(4)->get();
        $jobCharacteristics3 = JobCharacteristic3::orderBy('order')->take(4)->get();
        $topicData = TopicData::orderByDesc('date')->get();
        $datasets = Dataset::orderBy('order')->get()->groupBy('category');
        $highlightStatistics = HighlightStatistic::all();
        $highlightStatistics2 = HighlightStatisticSecondary::all();
        $visitorStats = Cache::remember('home:visitor-stats', now()->addMinutes(2), function (): array {
            try {
                return [
                    'visitCount' => Visitor::count(),
                    'todayVisits' => Visitor::whereDate('created_at', today())->count(),
                    'totalVisitors' => Visitor::distinct('ip_address')->count('ip_address'),
                    'todayVisitors' => Visitor::whereDate('created_at', today())->distinct('ip_address')->count('ip_address'),
                ];
            } catch (Throwable $exception) {
                Log::warning('Failed loading visitor stats for home page.', [
                    'message' => $exception->getMessage(),
                ]);

                return [
                    'visitCount' => 0,
                    'todayVisits' => 0,
                    'totalVisitors' => 0,
                    'todayVisitors' => 0,
                ];
            }
        });
        $visitCount = $visitorStats['visitCount'];
        $todayVisits = $visitorStats['todayVisits'];
        $totalVisitors = $visitorStats['totalVisitors'];
        $todayVisitors = $visitorStats['todayVisitors'];
        $mitraKerja = MitraKerja::where('divider', 'mitra')
            ->orderBy('sort')
            ->orderBy('id')
            ->get();
        $ads = collect();
        try {
            $response = Http::timeout(12)
                ->acceptJson()
                ->get('https://api.kemnaker.go.id/karirhub/catalogue/v1/industrial-vacancies', [
                    'platforms' => ['karirhub'],
                ]);

            if ($response->successful()) {
                $ads = collect($response->json('data', []))
                    ->map(function (array $ad) {
                        return (object) [
                            'image_base64' => '',
                            'mime_type' => '',
                            'company_logo_uri' => $ad['company_logo_uri'] ?? null,
                            'company_name' => $ad['company_name'] ?? '-',
                            'job_title' => $ad['title'] ?? '-',
                            'city' => $ad['city_name'] ?? null,
                            'province' => $ad['province_name'] ?? null,
                            'secret' => !($ad['show_salary'] ?? false) ? 1 : 0,
                            'salary_min' => (float) ($ad['min_salary_amount'] ?? 0),
                            'salary_max' => (float) ($ad['max_salary_amount'] ?? 0),
                        ];
                    })
                    ->values();
            }
        } catch (\Throwable $exception) {
            Log::warning('Failed fetching Karirhub vacancies for home page.', [
                'message' => $exception->getMessage(),
            ]);
        }

        if ($ads->isEmpty()) {
            $ads = KarirhubAds::latest()->get();
        }
        $welcomePopups = collect();
        if (Schema::hasTable('home_popup_settings')) {
            $welcomePopup = HomePopupSetting::query()->find(1);
            $isPopupEnabled = isset($welcomePopup) && (int) ($welcomePopup->is_enabled ?? 0) === 1;

            if ($isPopupEnabled && Schema::hasTable('home_popup_items')) {
                $welcomePopups = HomePopupItem::query()
                    ->where('setting_id', 1)
                    ->where('is_enabled', 1)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get();
            }

            // Backward compatibility for legacy single-popup data.
            if ($isPopupEnabled && $welcomePopups->isEmpty()) {
                $legacyTitle = trim((string) ($welcomePopup->title ?? ''));
                $legacySubtitle = trim((string) ($welcomePopup->subtitle ?? ''));
                $legacyImage = trim((string) ($welcomePopup->image_base64 ?? ''));
                if ($legacyTitle !== '' || $legacySubtitle !== '' || $legacyImage !== '') {
                    $welcomePopups = collect([$welcomePopup]);
                }
            }
        }

        // Additional categories for Informasi Terbaru section
        $spark = Information::where('subject', 'Seputar Pasar Kerja (SPARK)')->where('status', 'publik')->orderByDesc('date')->take(5)->get();
        $lmir = Information::where('subject', 'Labour Market Inteligence Report')->where('status', 'publik')->orderByDesc('date')->take(5)->get();
        $regulasi = Information::where('subject', 'Pedoman / Regulasi')->where('status', 'publik')->orderByDesc('date')->take(5)->get();
        $infografisSIPK = Information::where('subject', 'Infografis SIPK')->where('status', 'publik')->orderByDesc('date')->take(5)->get();
        $angkatanKerja = Information::where('subject', 'Angkatan Kerja')->where('status', 'publik')->orderByDesc('date')->take(5)->get();
        $infografisJobFair = Information::where('subject', 'Infografis Job Fair')->where('status', 'publik')->orderByDesc('date')->take(5)->get();

        return view('home', compact(
            'statistics',
            'heroStatistics',
            'statistik',
            'publikasi',
            'charts',
            'topLists',
            'contributions',
            'services',
            'news',
            'testimonials',
            'jobCharacteristics',
            'jobCharacteristics2',
            'jobCharacteristics3',
            'topicData',
            'datasets',
            'highlightStatistics',
            'highlightStatistics2',
            'visitCount',
            'todayVisits',
            'totalVisitors',
            'todayVisitors',
            'mitraKerja',
            'ads',
            'welcomePopups',
            'spark',
            'lmir',
            'regulasi',
            'infografisSIPK',
            'angkatanKerja',
            'infografisJobFair'
        ));
    }
} 