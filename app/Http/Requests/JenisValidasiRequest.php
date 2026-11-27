<?php

namespace App\Http\Requests;

use App\Models\JenisValidasi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisValidasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('settingValidasi');
        $id = $item instanceof JenisValidasi ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:15',
                Rule::unique('jenis_validasis', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jenis validasi wajib diisi.',
            'kode.max' => 'Kode jenis validasi maksimal 15 karakter.',
            'kode.unique' => 'Kode jenis validasi sudah terdaftar.',
            'nama.required' => 'Nama jenis validasi wajib diisi.',
            'nama.max' => 'Nama jenis validasi maksimal 100 karakter.',
        ];
    }
}
