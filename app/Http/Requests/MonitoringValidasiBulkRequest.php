<?php

namespace App\Http\Requests;

use App\Models\RiwayatPegawai;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MonitoringValidasiBulkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => [
                'required',
                Rule::in([
                    RiwayatPegawai::STATUS_DISETUJUI,
                    RiwayatPegawai::STATUS_DITOLAK,
                    RiwayatPegawai::STATUS_DITANGGUHKAN,
                ]),
            ],
            'jenis_riwayat_ids' => ['nullable', 'array'],
            'jenis_riwayat_ids.*' => ['integer', 'exists:jenis_riwayats,id'],
            'riwayat_ids' => ['nullable', 'array'],
            'riwayat_ids.*' => ['integer', 'exists:riwayat_pegawais,id'],
            'unit_kerja_id' => ['nullable', 'integer', 'exists:unit_kerjas,id'],
            'pegawai_id' => ['nullable', 'integer', 'exists:pegawais,id'],
            'catatan_validasi' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi validasi wajib dipilih.',
            'action.in' => 'Aksi validasi tidak valid.',
        ];
    }
}
