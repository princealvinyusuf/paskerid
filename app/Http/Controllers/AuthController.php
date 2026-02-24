<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showRegister(Request $request)
    {
        return view('auth.register', [
            'jobId' => $request->query('job'),
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'job_id' => ['nullable', 'integer'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('login', ['job' => $validated['job_id'] ?? null])
            ->with('success', 'Registrasi berhasil. Silakan login untuk melamar pekerjaan.');
    }

    public function showLogin(Request $request)
    {
        return view('auth.login', [
            'jobId' => $request->query('job'),
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'job_id' => ['nullable', 'integer'],
        ]);

        if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], true)) {
            return back()
                ->withErrors(['email' => 'Email atau password tidak valid.'])
                ->withInput($request->except('password'));
        }

        $request->session()->regenerate();

        if (!empty($credentials['job_id'])) {
            return redirect()
                ->route('minijobi.show', $credentials['job_id'])
                ->with('success', 'Login berhasil. Silakan klik "Lamar Sekarang" untuk mengirim lamaran.');
        }

        return redirect()->route('minijobi.index')->with('success', 'Login berhasil.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda berhasil logout.');
    }
}

