<?php

namespace App\Http\Requests;

use App\Models\KabupatenKota;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KabupatenKotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('kabupatenKota') ?? $this->route('kabupaten_kota');
        $id = $item instanceof KabupatenKota ? $item->id : $item;

        return [
            'provinsi_id' => ['required', 'integer', 'exists:provinsis,id'],
            'kode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('kabupaten_kotas', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'provinsi_id.required' => 'Provinsi wajib dipilih.',
            'provinsi_id.exists' => 'Provinsi tidak valid.',
            'kode.required' => 'Kode kabupaten/kota wajib diisi.',
            'kode.max' => 'Kode maksimal 10 karakter.',
            'kode.unique' => 'Kode kabupaten/kota sudah terdaftar.',
            'nama.required' => 'Nama kabupaten/kota wajib diisi.',
            'nama.max' => 'Nama kabupaten/kota maksimal 150 karakter.',
        ];
    }
}
