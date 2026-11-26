<?php

namespace App\Http\Requests;

use App\Models\JenisKehadiran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisKehadiranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('jenis_kehadiran');
        $id = $item instanceof JenisKehadiran ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('jenis_kehadirans', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'warna' => ['nullable', 'string', 'max:20', 'regex:/^#?[0-9A-Fa-f]{3,6}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jenis kehadiran wajib diisi.',
            'kode.max' => 'Kode maksimal 10 karakter.',
            'kode.unique' => 'Kode jenis kehadiran sudah terdaftar.',
            'nama.required' => 'Nama jenis kehadiran wajib diisi.',
            'nama.max' => 'Nama jenis kehadiran maksimal 150 karakter.',
            'warna.regex' => 'Format warna harus berupa kode hex yang valid (contoh: #F5BD24).',
        ];
    }
}
