<?php

namespace App\Http\Requests;

use App\Models\JenisPelanggaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisPelanggaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('jenis_pelanggaran');
        $id = $item instanceof JenisPelanggaran ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('jenis_pelanggarans', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jenis pelanggaran wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.unique' => 'Kode jenis pelanggaran sudah terdaftar.',
            'nama.required' => 'Nama pelanggaran wajib diisi.',
            'nama.max' => 'Nama pelanggaran maksimal 150 karakter.',
        ];
    }
}
