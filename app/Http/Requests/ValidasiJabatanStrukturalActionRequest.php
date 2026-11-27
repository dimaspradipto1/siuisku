<?php

namespace App\Http\Requests;

use App\Models\RiwayatJabatanStruktural;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidasiJabatanStrukturalActionRequest extends FormRequest
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
                    RiwayatJabatanStruktural::STATUS_DISETUJUI,
                    RiwayatJabatanStruktural::STATUS_DITOLAK,
                ]),
            ],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:riwayat_jabatan_strukturals,id'],
            'catatan_validasi' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi validasi wajib dipilih.',
            'action.in' => 'Aksi validasi hanya boleh Setujui atau Tolak.',
            'ids.required' => 'Pilih minimal satu data riwayat jabatan struktural terlebih dahulu.',
            'ids.min' => 'Pilih minimal satu data riwayat jabatan struktural terlebih dahulu.',
        ];
    }
}
