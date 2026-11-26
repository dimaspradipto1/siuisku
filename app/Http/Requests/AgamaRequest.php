<?php

namespace App\Http\Requests;

use App\Models\Agama;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgamaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('agama');
        $id = $item instanceof Agama ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('agamas', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode agama wajib diisi.',
            'kode.max' => 'Kode agama maksimal 10 karakter.',
            'kode.unique' => 'Kode agama sudah terdaftar.',
            'nama.required' => 'Nama agama wajib diisi.',
            'nama.max' => 'Nama agama maksimal 100 karakter.',
        ];
    }
}
