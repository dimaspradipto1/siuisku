<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('modul');
        }

        return view('layouts.auth.login');
    }

    public function loginproses(AuthRequest $request)
    {
        $credentials = $request->credentials();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $device = ActivityLog::parseUserAgent($request->userAgent())['label'];
            ActivityLog::record(
                Auth::user(),
                'auth',
                'Login berhasil',
                "Masuk ke sistem melalui {$device}.",
                'accent',
            );

            return redirect()->intended(route('modul'));
        }

        $login = $request->string('login')->toString();
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $existingUser = User::where($field, $login)->first();

        if ($existingUser) {
            ActivityLog::record(
                $existingUser,
                'security',
                'Percobaan login gagal',
                'Password salah saat mencoba masuk ke akun.',
                'danger',
            );
        }

        return back()
            ->withErrors(['login' => 'Username/email atau password salah.'])
            ->onlyInput('login');
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('modul');
        }

        return view('layouts.auth.register');
    }

    public function registerproses(AuthRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'roles' => 'staff',
            'password' => $validated['password'],
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::record(
                Auth::user(),
                'auth',
                'Logout dari sistem',
                'Pengguna keluar dari sesi aktif.',
                'info',
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
