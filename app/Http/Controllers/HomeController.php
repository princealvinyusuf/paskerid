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
        $topLists = TopList::all();
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