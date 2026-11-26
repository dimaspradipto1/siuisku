<?php

namespace App\Http\Requests;

use App\Models\JenjangPendidikan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenjangPendidikanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('jenjang_pendidikan');
        $id = $item instanceof JenjangPendidikan ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:15',
                Rule::unique('jenjang_pendidikans', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'nama_en' => ['nullable', 'string', 'max:150'],
            'urutan' => ['required', 'integer', 'min:0', 'max:999'],
            'is_perguruan_tinggi' => ['nullable', 'boolean'],
            'is_pasca_sarjana' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jenjang wajib diisi.',
            'kode.max' => 'Kode jenjang maksimal 15 karakter.',
            'kode.unique' => 'Kode jenjang sudah terdaftar.',
            'nama.required' => 'Nama jenjang pendidikan wajib diisi.',
            'nama.max' => 'Nama jenjang pendidikan maksimal 150 karakter.',
            'urutan.required' => 'Urutan wajib diisi.',
            'urutan.integer' => 'Urutan harus berupa angka.',
        ];
    }
}
