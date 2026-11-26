<?php

namespace App\Http\Requests;

use App\Models\Eselon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EselonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('eselon');
        $id = $item instanceof Eselon ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('eselons', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'aktif' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode eselon wajib diisi.',
            'kode.max' => 'Kode eselon maksimal 20 karakter.',
            'kode.unique' => 'Kode eselon sudah terdaftar.',
            'nama.required' => 'Nama eselon wajib diisi.',
            'nama.max' => 'Nama eselon maksimal 150 karakter.',
        ];
    }
}
