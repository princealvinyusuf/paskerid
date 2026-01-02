<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HighlightStatistic;
use App\Models\HighlightStatisticSecondary;
use App\Models\Statistic;

class HighlightStatisticController extends Controller
{
    /**
     * Return primary, secondary, and tertiary (general statistics) data as JSON.
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

        // Tertiary data from general statistics table
        $tertiary = Statistic::orderBy('order')->get([
            'id',
            'title',
            'value',
            'unit',
            'description',
            'type',
            'order',
            'logo',
        ]);

        return response()->json([
            'primary' => $primary,
            'secondary' => $secondary,
            'tertiary' => $tertiary,
        ]);
    }
}


