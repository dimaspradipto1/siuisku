<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPangkat extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_DIAJUKAN = 'diajukan';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_DITANGGUHKAN = 'ditangguhkan';

    public const STATUS_OPTIONS = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_DIAJUKAN => 'Diajukan',
        self::STATUS_DISETUJUI => 'Disetujui',
        self::STATUS_DITOLAK => 'Ditolak',
        self::STATUS_DITANGGUHKAN => 'Ditangguhkan',
    ];

    protected $fillable = [
        'pegawai_id',
        'pangkat_id',
        'jenis_sk_id',
        'tmt_pangkat',
        'nomor_sk',
        'tanggal_sk',
        'file_pangkat',
        'masa_kerja_tahun',
        'masa_kerja_bulan',
        'keterangan',
        'tanggal_diajukan',
        'status',
        'catatan_validasi',
        'validated_by',
        'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'tmt_pangkat' => 'date',
            'tanggal_sk' => 'date',
            'tanggal_diajukan' => 'date',
            'masa_kerja_tahun' => 'integer',
            'masa_kerja_bulan' => 'integer',
            'validated_at' => 'datetime',
        ];
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function pangkat(): BelongsTo
    {
        return $this->belongsTo(Pangkat::class);
    }

    public function jenisSk(): BelongsTo
    {
        return $this->belongsTo(JenisSk::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function statusLabel(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? ucfirst((string) $this->status);
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_DIAJUKAN => 'bg-info-subtle text-info',
            self::STATUS_DISETUJUI => 'bg-success-subtle text-success',
            self::STATUS_DITOLAK => 'bg-danger-subtle text-danger',
            self::STATUS_DITANGGUHKAN => 'bg-warning-subtle text-warning',
            default => 'bg-secondary-subtle text-secondary',
        };
    }
}
