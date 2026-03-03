<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunityForumController extends Controller
{
    private const SESSION_KEY = 'cf_access_granted';

    public function showGate(Request $request): View|RedirectResponse
    {
        if ((bool) $request->session()->get(self::SESSION_KEY, false)) {
            return redirect()->route('cf.index');
        }

        return view('cf.gate');
    }

    public function verifyPasscode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'passcode' => 'required|string|max:255',
        ]);

        $expectedPasscode = (string) env('CF_MENU_PASSCODE', '01062025');
        $submittedPasscode = trim((string) $validated['passcode']);

        if ($submittedPasscode !== '' && hash_equals($expectedPasscode, $submittedPasscode)) {
            $request->session()->put(self::SESSION_KEY, true);
            $request->session()->regenerate();

            return redirect()->route('cf.index');
        }

        return redirect()
            ->route('cf.gate')
            ->withErrors(['passcode' => 'Passcode tidak valid.'])
            ->withInput();
    }

    public function index(Request $request): View|RedirectResponse
    {
        if (!(bool) $request->session()->get(self::SESSION_KEY, false)) {
            return redirect()
                ->route('cf.gate')
                ->withErrors(['passcode' => 'Masukkan passcode untuk mengakses menu CF.']);
        }

        return view('cf.index');
    }
}
