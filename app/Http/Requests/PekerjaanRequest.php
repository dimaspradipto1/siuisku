<?php

namespace App\Http\Requests;

use App\Models\Pekerjaan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PekerjaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('pekerjaan');
        $id = $item instanceof Pekerjaan ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('pekerjaans', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode pekerjaan wajib diisi.',
            'kode.max' => 'Kode pekerjaan maksimal 10 karakter.',
            'kode.unique' => 'Kode pekerjaan sudah terdaftar.',
            'nama.required' => 'Nama pekerjaan wajib diisi.',
            'nama.max' => 'Nama pekerjaan maksimal 150 karakter.',
        ];
    }
}
