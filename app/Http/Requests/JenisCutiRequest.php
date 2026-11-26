<?php

namespace App\Http\Requests;

use App\Models\JenisCuti;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisCutiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('jenis_cuti');
        $id = $item instanceof JenisCuti ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('jenis_cutis', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'standar_cuti' => ['required', 'integer', 'min:0', 'max:365'],
            'format_nomor_surat' => ['nullable', 'string', 'max:150'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jenis cuti wajib diisi.',
            'kode.max' => 'Kode maksimal 10 karakter.',
            'kode.unique' => 'Kode jenis cuti sudah terdaftar.',
            'nama.required' => 'Nama jenis cuti wajib diisi.',
            'nama.max' => 'Nama jenis cuti maksimal 150 karakter.',
            'standar_cuti.required' => 'Standar cuti (hari) wajib diisi.',
            'standar_cuti.integer' => 'Standar cuti harus berupa angka.',
            'standar_cuti.min' => 'Standar cuti minimal 0 hari.',
        ];
    }
}
