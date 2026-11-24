<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedCategory = $request->query('category');

        $baseQuery = ActivityLog::query()
            ->where('user_id', $user->id)
            ->latest();

        $allLogs = (clone $baseQuery)->get();

        $loginCount = $allLogs->where('category', 'auth')->where('type', 'accent')->count();
        $securityCount = $allLogs->where('category', 'security')->count();
        $changeCount = $allLogs->whereIn('category', ['profile', 'system'])->count();
        $failedCount = $allLogs->where('type', 'danger')->count();

        if (filled($selectedCategory)) {
            $baseQuery->where('category', $selectedCategory);
        }

        $logs = $baseQuery->limit(100)->get();

        $groupedLogs = $logs->groupBy(function (ActivityLog $log): string {
            if ($log->created_at->isToday()) {
                return 'Hari Ini';
            }

            if ($log->created_at->isYesterday()) {
                return 'Kemarin';
            }

            return $log->created_at->translatedFormat('d F Y');
        });

        $currentSessionId = $request->session()->getId();
        $sessions = collect();

        if (Schema::hasTable('sessions')) {
            $sessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->orderByDesc('last_activity')
                ->limit(5)
                ->get()
                ->map(function (object $session) use ($currentSessionId): array {
                    $parsed = ActivityLog::parseUserAgent($session->user_agent ?? '');

                    return [
                        'name' => $parsed['label'],
                        'icon' => $parsed['icon'],
                        'ip' => $session->ip_address ?: '127.0.0.1',
                        'is_current' => $session->id === $currentSessionId,
                        'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    ];
                });
        }

        if ($sessions->isEmpty()) {
            $parsed = ActivityLog::parseUserAgent($request->userAgent());
            $sessions = collect([[
                'name' => $parsed['label'],
                'icon' => $parsed['icon'],
                'ip' => $request->ip() ?: '127.0.0.1',
                'is_current' => true,
                'last_active' => 'Aktif sekarang',
            ]]);
        }

        return view('layouts.pages.activity.index', compact(
            'user',
            'groupedLogs',
            'selectedCategory',
            'loginCount',
            'securityCount',
            'changeCount',
            'failedCount',
            'sessions',
        ));
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $logs = ActivityLog::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $filename = 'activity-log-'.$user->username.'-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($logs): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Waktu', 'Kategori', 'Aktivitas', 'Keterangan', 'IP Address']);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at?->format('Y-m-d H:i:s'),
                    $log->category,
                    $log->title,
                    $log->description,
                    $log->ip_address,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
