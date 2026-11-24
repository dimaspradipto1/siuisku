<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'username', 'email', 'roles', 'photo', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getInitialsAttribute(): string
    {
        $words = collect(preg_split('/\s+/', trim((string) $this->name)))
            ->filter(fn (?string $word) => filled($word) && ! preg_match('/^(dr|prof|ir|h|hj)\.?$/i', $word))
            ->values();

        if ($words->isEmpty()) {
            $words = collect(preg_split('/\s+/', trim((string) $this->name)))->filter()->values();
        }

        if ($words->count() >= 2) {
            return strtoupper(mb_substr($words[0], 0, 1).mb_substr($words[1], 0, 1));
        }

        $single = $words->first() ?? 'U';

        return strtoupper(mb_substr($single, 0, 2));
    }

    public function getAvatarUrlAttribute(): string
    {
        if (filled($this->photo) && Storage::disk('public')->exists($this->photo)) {
            return asset('storage/'.$this->photo);
        }

        $palette = [
            ['#1d4ed8', '#dbeafe'],
            ['#0f766e', '#ccfbf1'],
            ['#7c3aed', '#ede9fe'],
            ['#c2410c', '#ffedd5'],
            ['#be123c', '#ffe4e6'],
            ['#0369a1', '#e0f2fe'],
            ['#4338ca', '#e0e7ff'],
            ['#15803d', '#dcfce7'],
        ];

        $hash = abs(crc32(strtolower((string) ($this->username ?: $this->name))));
        [$fg, $bg] = $palette[$hash % count($palette)];
        $initials = htmlspecialchars($this->initials, ENT_XML1 | ENT_QUOTES, 'UTF-8');

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128">'
            .'<rect width="128" height="128" rx="64" fill="'.$bg.'"/>'
            .'<text x="50%" y="53%" dominant-baseline="middle" text-anchor="middle" '
            .'font-family="Manrope, Arial, sans-serif" font-size="48" font-weight="700" fill="'.$fg.'">'
            .$initials
            .'</text></svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }
}
