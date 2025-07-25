<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\Models\Visitor;
use App\Models\MitraKerja;

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
        $visitCount = Visitor::count();
        $mitraKerja = MitraKerja::where('divider', 'mitra')->get();

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
            'visitCount',
            'mitraKerja'
        ));
    }
} 