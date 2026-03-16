<?php

namespace App\Http\Controllers;

use App\Models\DashboardDistribution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardDistributionController extends Controller
{
    private const FORECASTING_SESSION_KEY = 'dashboard_distribution_forecasting_access_granted';

    public function index()
    {
        $dashboard = DashboardDistribution::first();
        return view('dashboard_distribution.index', compact('dashboard'));
    }

    public function verifyForecastingPasscode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'passcode' => 'required|string|max:255',
        ]);

        $expectedPasscode = (string) env('FORECASTING_MENU_PASSCODE', '01062025');
        $submittedPasscode = trim((string) $validated['passcode']);

        if ($submittedPasscode !== '' && hash_equals($expectedPasscode, $submittedPasscode)) {
            $request->session()->put(self::FORECASTING_SESSION_KEY, true);
            $request->session()->regenerate();

            return redirect()->route('dashboard.distribution.forecasting');
        }

        return redirect()
            ->route('dashboard.distribution')
            ->withErrors(['passcode' => 'Passcode tidak valid.'])
            ->withInput();
    }

    public function forecasting(Request $request): View|RedirectResponse
    {
        if (!(bool) $request->session()->get(self::FORECASTING_SESSION_KEY, false)) {
            return redirect()
                ->route('dashboard.distribution')
                ->withErrors(['passcode' => 'Masukkan passcode untuk mengakses menu Forecasting.']);
        }

        return view('dashboard_distribution.forecasting');
    }
} 