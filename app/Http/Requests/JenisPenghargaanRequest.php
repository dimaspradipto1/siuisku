<?php

namespace App\Http\Requests;

use App\Models\JenisPenghargaan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisPenghargaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('jenis_penghargaan');
        $id = $item instanceof JenisPenghargaan ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('jenis_penghargaans', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jenis penghargaan wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.unique' => 'Kode jenis penghargaan sudah terdaftar.',
            'nama.required' => 'Nama penghargaan wajib diisi.',
            'nama.max' => 'Nama penghargaan maksimal 150 karakter.',
        ];
    }
}
