<?php

namespace App\Http\Requests;

use App\Models\JenisLuaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisLuaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('jenis_luaran');
        $id = $item instanceof JenisLuaran ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('jenis_luarans', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jenis luaran wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.unique' => 'Kode jenis luaran sudah terdaftar.',
            'nama.required' => 'Nama jenis luaran wajib diisi.',
            'nama.max' => 'Nama jenis luaran maksimal 150 karakter.',
        ];
    }
}
