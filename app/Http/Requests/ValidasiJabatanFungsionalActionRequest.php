<?php

namespace App\Http\Requests;

use App\Models\RiwayatJabatanFungsional;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidasiJabatanFungsionalActionRequest extends FormRequest
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
                    RiwayatJabatanFungsional::STATUS_DISETUJUI,
                    RiwayatJabatanFungsional::STATUS_DITOLAK,
                ]),
            ],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:riwayat_jabatan_fungsionals,id'],
            'catatan_validasi' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi validasi wajib dipilih.',
            'action.in' => 'Aksi validasi hanya boleh Setujui atau Tolak.',
            'ids.required' => 'Pilih minimal satu data riwayat jabatan fungsional terlebih dahulu.',
            'ids.min' => 'Pilih minimal satu data riwayat jabatan fungsional terlebih dahulu.',
        ];
    }
}
