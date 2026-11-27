<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPegawai extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_DIAJUKAN = 'diajukan';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_DITANGGUHKAN = 'ditangguhkan';

    public const STATUSES = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_DIAJUKAN => 'Diajukan',
        self::STATUS_DISETUJUI => 'Disetujui',
        self::STATUS_DITOLAK => 'Ditolak',
        self::STATUS_DITANGGUHKAN => 'Ditangguhkan',
    ];

    protected $fillable = [
        'pegawai_id',
        'jenis_riwayat_id',
        'judul',
        'keterangan',
        'tanggal_pengajuan',
        'status',
        'catatan_validasi',
        'validated_by',
        'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'date',
            'validated_at' => 'datetime',
        ];
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function jenisRiwayat(): BelongsTo
    {
        return $this->belongsTo(JenisRiwayat::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_DIAJUKAN => 'bg-danger-subtle text-danger',
            self::STATUS_DISETUJUI => 'bg-success-subtle text-success',
            self::STATUS_DITOLAK => 'bg-dark-subtle text-dark',
            self::STATUS_DITANGGUHKAN => 'bg-warning-subtle text-warning',
            default => 'bg-secondary-subtle text-secondary',
        };
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }
}
