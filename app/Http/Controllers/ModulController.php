<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ModulController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $userRoles = collect(explode(',', (string) $user->roles))
            ->map(fn (string $role) => trim($role))
            ->filter()
            ->values();

        $modules = [
            [
                'key' => 'simpeg',
                'name' => 'SIM Kepegawaian',
                'short' => 'SIMPEG',
                'icon' => 'bi-people-fill',
                'color' => '#ea580c',
                'bg' => '#fff7ed',
                'available' => true,
                'route' => route('dashboard'),
            ],
            [
                'key' => 'siakad',
                'name' => 'SIM Akademik',
                'short' => 'SIAKAD',
                'icon' => 'bi-mortarboard-fill',
                'color' => '#0284c7',
                'bg' => '#f0f9ff',
                'available' => false,
                'route' => null,
            ],
            [
                'key' => 'akreditasi',
                'name' => 'SIM Akreditasi',
                'short' => 'Akreditasi',
                'icon' => 'bi-patch-check-fill',
                'color' => '#dc2626',
                'bg' => '#fef2f2',
                'available' => false,
                'route' => null,
            ],
            [
                'key' => 'elearning',
                'name' => 'E-Learning',
                'short' => 'LMS',
                'icon' => 'bi-book-half',
                'color' => '#16a34a',
                'bg' => '#f0fdf4',
                'available' => false,
                'route' => null,
            ],
            [
                'key' => 'pelaporan',
                'name' => 'SIM Dashboard & Pelaporan',
                'short' => 'Pelaporan',
                'icon' => 'bi-bar-chart-line-fill',
                'color' => '#0d9488',
                'bg' => '#f0fdfa',
                'available' => false,
                'route' => null,
            ],
            [
                'key' => 'helpdesk',
                'name' => 'SIM Support & Helpdesk',
                'short' => 'Helpdesk',
                'icon' => 'bi-headset',
                'color' => '#7c3aed',
                'bg' => '#f5f3ff',
                'available' => false,
                'route' => null,
            ],
        ];

        return view('layouts.modul.index', compact('user', 'userRoles', 'modules'));
    }
}
