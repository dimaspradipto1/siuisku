<?php

namespace App\Http\Requests;

use App\Models\Suku;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('suku');
        $id = $item instanceof Suku ? $item->id : $item;

        return [
            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('sukus', 'nama')->ignore($id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama suku wajib diisi.',
            'nama.max' => 'Nama suku maksimal 100 karakter.',
            'nama.unique' => 'Nama suku sudah terdaftar.',
        ];
    }
}
