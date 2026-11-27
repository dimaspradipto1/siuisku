<?php

namespace App\Http\Requests;

use App\Models\GolonganDarah;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GolonganDarahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('golongan_darah');
        $id = $item instanceof GolonganDarah ? $item->id : $item;

        return [
            'nama' => [
                'required',
                'string',
                'max:10',
                Rule::unique('golongan_darahs', 'nama')->ignore($id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Golongan darah wajib diisi.',
            'nama.max' => 'Golongan darah maksimal 10 karakter.',
            'nama.unique' => 'Golongan darah sudah terdaftar.',
        ];
    }
}
