<?php

namespace App\Http\Requests;

use App\Models\Bahasa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BahasaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('bahasa');
        $id = $item instanceof Bahasa ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('bahasas', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode bahasa wajib diisi.',
            'kode.max' => 'Kode bahasa maksimal 10 karakter.',
            'kode.unique' => 'Kode bahasa sudah terdaftar.',
            'nama.required' => 'Nama bahasa wajib diisi.',
            'nama.max' => 'Nama bahasa maksimal 100 karakter.',
        ];
    }
}
