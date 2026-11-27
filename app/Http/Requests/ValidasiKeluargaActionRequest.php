<?php

namespace App\Http\Requests;

use App\Models\KeluargaPegawai;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidasiKeluargaActionRequest extends FormRequest
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
                    KeluargaPegawai::STATUS_DISETUJUI,
                    KeluargaPegawai::STATUS_DITOLAK,
                ]),
            ],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:keluarga_pegawais,id'],
            'catatan_validasi' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi validasi wajib dipilih.',
            'action.in' => 'Aksi validasi hanya boleh Setujui atau Tolak.',
            'ids.required' => 'Pilih minimal satu data keluarga terlebih dahulu.',
            'ids.min' => 'Pilih minimal satu data keluarga terlebih dahulu.',
        ];
    }
}
