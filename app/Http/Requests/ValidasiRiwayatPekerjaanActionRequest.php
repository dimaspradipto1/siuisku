<?php

namespace App\Http\Requests;

use App\Models\RiwayatPekerjaan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidasiRiwayatPekerjaanActionRequest extends FormRequest
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
                    RiwayatPekerjaan::STATUS_DISETUJUI,
                    RiwayatPekerjaan::STATUS_DITOLAK,
                    RiwayatPekerjaan::STATUS_DITANGGUHKAN,
                ]),
            ],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:riwayat_pekerjaans,id'],
            'catatan_validasi' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi validasi wajib dipilih.',
            'action.in' => 'Aksi validasi hanya boleh Setujui, Tolak, atau Tangguhkan.',
            'ids.required' => 'Pilih minimal satu data riwayat pekerjaan terlebih dahulu.',
            'ids.min' => 'Pilih minimal satu data riwayat pekerjaan terlebih dahulu.',
        ];
    }
}
