<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
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

    public function loginproses(LoginRequest $request)
    {
        if (Auth::attempt($request->credentials())) {
            $request->session()->regenerate();

            return redirect()->intended(route('modul'));
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

    public function registerproses(RegisterRequest $request)
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
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
