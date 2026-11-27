<?php

namespace App\Http\Requests;

use App\Models\RiwayatPangkat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidasiPangkatActionRequest extends FormRequest
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
                    RiwayatPangkat::STATUS_DISETUJUI,
                    RiwayatPangkat::STATUS_DITOLAK,
                    RiwayatPangkat::STATUS_DITANGGUHKAN,
                ]),
            ],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:riwayat_pangkats,id'],
            'catatan_validasi' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi validasi wajib dipilih.',
            'action.in' => 'Aksi validasi hanya boleh Setujui, Tolak, atau Tangguhkan.',
            'ids.required' => 'Pilih minimal satu data riwayat pangkat terlebih dahulu.',
            'ids.min' => 'Pilih minimal satu data riwayat pangkat terlebih dahulu.',
        ];
    }
}
