<?php

namespace App\Http\Requests;

use App\Models\Provinsi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProvinsiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('provinsi');
        $id = $item instanceof Provinsi ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('provinsis', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode provinsi wajib diisi.',
            'kode.max' => 'Kode provinsi maksimal 10 karakter.',
            'kode.unique' => 'Kode provinsi sudah terdaftar.',
            'nama.required' => 'Nama provinsi wajib diisi.',
            'nama.max' => 'Nama provinsi maksimal 150 karakter.',
        ];
    }
}
