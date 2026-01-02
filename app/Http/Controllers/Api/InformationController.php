<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    /**
     * Return a list of information records as JSON.
     *
     * Optional filters via query string:
     * - type: filter by type (e.g. statistik, publikasi)
     * - subject: filter by subject
     * - status: filter by status (defaults to 'publik' if not provided)
     */
    public function index(Request $request)
    {
        $query = Information::query();

        // Default: only public items, unless status explicitly set
        $status = $request->query('status', 'publik');
        if ($status !== null) {
            $query->where('status', $status);
        }

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        if ($subject = $request->query('subject')) {
            $query->where('subject', $subject);
        }

        $information = $query
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get([
                'id',
                'title',
                'description',
                'date',
                'type',
                'subject',
                'file_url',
                'iframe_url',
                'status',
                'created_at',
                'updated_at',
            ]);

        return response()->json([
            'data' => $information,
        ]);
    }
}


