<?php

namespace App\Http\Requests;

use App\Models\JenisSertifikasi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisSertifikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('jenis_sertifikasi');
        $id = $item instanceof JenisSertifikasi ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('jenis_sertifikasis', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'kategori' => [
                'required',
                'string',
                Rule::in(JenisSertifikasi::KATEGORI_OPTIONS),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jenis sertifikasi wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.unique' => 'Kode jenis sertifikasi sudah terdaftar.',
            'nama.required' => 'Nama sertifikasi wajib diisi.',
            'nama.max' => 'Nama sertifikasi maksimal 150 karakter.',
            'kategori.required' => 'Jenis sertifikasi wajib dipilih.',
            'kategori.in' => 'Jenis sertifikasi tidak valid.',
        ];
    }
}
