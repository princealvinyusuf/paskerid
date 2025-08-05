<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $news = $query->orderByDesc('date')->paginate(6);

        return view('news.index', compact('news'));
    }

    public function show($id)
    {
        $news = \App\Models\News::findorFail($id);
        $popularNews = \App\Models\News::orderBy('Created_at', 'desc')->limit(5)->get();

        return view('news.DetailBerita', compact('news','popularNews'));
    }

    public function like($id)
    {
        $news = \App\Models\News::findOrFail($id);
        $news->likes++;
        $news->save();
        return response()->json(['likes' => $news->likes]);
    }
}
