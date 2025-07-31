<?php

namespace App\Http\Controllers;

use App\Models\MiniVideo;
use Illuminate\Http\Request;

class MiniVideoController extends Controller
{
    // Fetch all videos for the mini player (ordered)
    public function index()
    {
        $videos = MiniVideo::orderBy('order')->get();
        return response()->json($videos);
    }

    // (Optional) Store a new video
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|url',
            'order' => 'nullable|integer',
        ]);
        $video = MiniVideo::create($request->all());
        return response()->json($video, 201);
    }

    // (Optional) Delete a video
    public function destroy($id)
    {
        $video = MiniVideo::findOrFail($id);
        $video->delete();
        return response()->json(null, 204);
    }
} 