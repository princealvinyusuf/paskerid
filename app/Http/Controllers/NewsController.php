<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsLike;

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

    public function like($id, Request $request)
    {
        $news = News::findOrFail($id);
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');

        $like = NewsLike::where('news_id', $news->id)
            ->where('ip_address', $ip)
            ->where('user_agent', $userAgent)
            ->first();

        if ($like) {
            // Unlike
            $like->delete();
            $news->likes = max(0, $news->likes - 1);
            $news->save();
            $liked = false;
        } else {
            // Like
            NewsLike::create([
                'news_id' => $news->id,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);
            $news->likes++;
            $news->save();
            $liked = true;
        }
        return response()->json(['likes' => $news->likes, 'liked' => $liked]);
    }

    public function likeStatus($id, Request $request)
    {
        $news = News::findOrFail($id);
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');
        $liked = \App\Models\NewsLike::where('news_id', $news->id)
            ->where('ip_address', $ip)
            ->where('user_agent', $userAgent)
            ->exists();
        return response()->json([
            'liked' => $liked,
            'likes' => $news->likes,
        ]);
    }
}
