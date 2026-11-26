<?php

namespace App\Http\Requests;

use App\Models\JenisPkm;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisPkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('jenis_pkm');
        $id = $item instanceof JenisPkm ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('jenis_pkms', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jenis PKM wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.unique' => 'Kode jenis PKM sudah terdaftar.',
            'nama.required' => 'Nama PKM wajib diisi.',
            'nama.max' => 'Nama PKM maksimal 150 karakter.',
        ];
    }
}
