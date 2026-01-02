<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HighlightStatistic;
use App\Models\HighlightStatisticSecondary;

class HighlightStatisticController extends Controller
{
    /**
     * Return primary and secondary highlight statistics as JSON.
     */
    public function index()
    {
        $primary = HighlightStatistic::orderBy('id')->get([
            'id',
            'title',
            'value',
            'unit',
            'description',
            'logo',
        ]);

        $secondary = HighlightStatisticSecondary::orderBy('id')->get([
            'id',
            'title',
            'value',
            'unit',
            'description',
            'logo',
        ]);

        return response()->json([
            'primary' => $primary,
            'secondary' => $secondary,
        ]);
    }
}


