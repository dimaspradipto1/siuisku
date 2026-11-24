<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'category',
    'type',
    'title',
    'description',
    'ip_address',
    'user_agent',
])]
class ActivityLog extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(
        User|int $user,
        string $category,
        string $title,
        ?string $description = null,
        string $type = 'info',
    ): self {
        $userId = $user instanceof User ? $user->id : $user;
        $req = request();

        return self::create([
            'user_id' => $userId,
            'category' => $category,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'ip_address' => $req?->ip(),
            'user_agent' => $req?->userAgent(),
        ]);
    }

    public static function parseUserAgent(?string $ua): array
    {
        $ua = (string) $ua;

        $browser = match (true) {
            str_contains($ua, 'Edg') => 'Edge',
            str_contains($ua, 'Chrome') => 'Chrome',
            str_contains($ua, 'Firefox') => 'Firefox',
            str_contains($ua, 'Safari') => 'Safari',
            default => 'Browser',
        };

        $os = match (true) {
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'iPhone') => 'iPhone',
            str_contains($ua, 'iPad') => 'iPad',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'Mac OS') => 'macOS',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'Device',
        };

        $icon = match ($os) {
            'iPhone', 'Android' => 'bi-phone',
            'iPad' => 'bi-tablet',
            default => 'bi-laptop',
        };

        return [
            'label' => "{$browser} on {$os}",
            'icon' => $icon,
        ];
    }
}
