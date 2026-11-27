<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatHubunganKerja extends Model
{
    protected $table = 'riwayat_hubungan_kerjas';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_DIAJUKAN = 'diajukan';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_OPTIONS = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_DIAJUKAN => 'Diajukan',
        self::STATUS_DISETUJUI => 'Disetujui',
        self::STATUS_DITOLAK => 'Ditolak',
    ];

    protected $fillable = [
        'pegawai_id',
        'hubungan_kerja_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'nomor_sk',
        'tanggal_sk',
        'file_hubungan_kerja',
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
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'tanggal_sk' => 'date',
            'tanggal_diajukan' => 'date',
            'validated_at' => 'datetime',
        ];
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function hubunganKerja(): BelongsTo
    {
        return $this->belongsTo(HubunganKerja::class);
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
            self::STATUS_DIAJUKAN => 'bg-warning-subtle text-warning',
            self::STATUS_DISETUJUI => 'bg-success-subtle text-success',
            self::STATUS_DITOLAK => 'bg-danger-subtle text-danger',
            default => 'bg-secondary-subtle text-secondary',
        };
    }
}
