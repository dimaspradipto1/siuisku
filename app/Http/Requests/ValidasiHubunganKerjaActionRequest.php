<?php

namespace App\Http\Requests;

use App\Models\RiwayatHubunganKerja;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidasiHubunganKerjaActionRequest extends FormRequest
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
                    RiwayatHubunganKerja::STATUS_DISETUJUI,
                    RiwayatHubunganKerja::STATUS_DITOLAK,
                ]),
            ],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:riwayat_hubungan_kerjas,id'],
            'catatan_validasi' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi validasi wajib dipilih.',
            'action.in' => 'Aksi validasi hanya boleh Setujui atau Tolak.',
            'ids.required' => 'Pilih minimal satu data riwayat hubungan kerja terlebih dahulu.',
            'ids.min' => 'Pilih minimal satu data riwayat hubungan kerja terlebih dahulu.',
        ];
    }
}
