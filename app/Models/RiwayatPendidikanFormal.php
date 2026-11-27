<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPendidikanFormal extends Model
{
    protected $table = 'riwayat_pendidikan_formals';

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
        'jenjang_pendidikan_id',
        'perguruan_tinggi_id',
        'prodi_perguruan_tinggi_id',
        'gelar_akademik_id',
        'nama_institusi',
        'program_studi',
        'tahun_masuk',
        'tahun_lulus',
        'no_ijazah',
        'tanggal_ijazah',
        'file_ijazah',
        'ipk',
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
            'tahun_masuk' => 'integer',
            'tahun_lulus' => 'integer',
            'tanggal_ijazah' => 'date',
            'ipk' => 'decimal:2',
            'tanggal_diajukan' => 'date',
            'validated_at' => 'datetime',
        ];
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function jenjangPendidikan(): BelongsTo
    {
        return $this->belongsTo(JenjangPendidikan::class);
    }

    public function perguruanTinggi(): BelongsTo
    {
        return $this->belongsTo(PerguruanTinggi::class);
    }

    public function prodiPerguruanTinggi(): BelongsTo
    {
        return $this->belongsTo(ProdiPerguruanTinggi::class);
    }

    public function gelarAkademik(): BelongsTo
    {
        return $this->belongsTo(GelarAkademik::class);
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
