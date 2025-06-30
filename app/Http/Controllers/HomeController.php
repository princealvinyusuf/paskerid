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

class HomeController extends Controller
{
    public function index()
    {
        $statistics = Statistic::orderBy('order')->get();
        $information = Information::orderByDesc('date')->take(5)->get();
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

        return view('home', compact(
            'statistics',
            'information',
            'charts',
            'topLists',
            'contributions',
            'services',
            'news',
            'testimonials'
        ));
    }
} 