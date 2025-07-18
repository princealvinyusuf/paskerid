<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TopicData;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TopicDataController extends Controller
{
    public function download($id)
    {
        $topic = TopicData::findOrFail($id);
        $filePath = $topic->file_url;
        // If file_url is a full URL, redirect. If it's a relative path, serve from public/documents
        if (filter_var($filePath, FILTER_VALIDATE_URL)) {
            return redirect($filePath);
        }
        $publicPath = public_path('documents/' . ltrim($filePath, '/'));
        if (!file_exists($publicPath)) {
            abort(404, 'File not found');
        }
        return response()->download($publicPath, basename($publicPath));
    }
} 