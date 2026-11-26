<?php

namespace App\Http\Requests;

use App\Models\Kecamatan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KecamatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('kecamatan');
        $id = $item instanceof Kecamatan ? $item->id : $item;

        return [
            'kabupaten_kota_id' => ['required', 'integer', 'exists:kabupaten_kotas,id'],
            'kode' => [
                'required',
                'string',
                'max:15',
                Rule::unique('kecamatans', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'kabupaten_kota_id.required' => 'Kabupaten/Kota wajib dipilih.',
            'kabupaten_kota_id.exists' => 'Kabupaten/Kota tidak valid.',
            'kode.required' => 'Kode kecamatan wajib diisi.',
            'kode.max' => 'Kode kecamatan maksimal 15 karakter.',
            'kode.unique' => 'Kode kecamatan sudah terdaftar.',
            'nama.required' => 'Nama kecamatan wajib diisi.',
            'nama.max' => 'Nama kecamatan maksimal 150 karakter.',
        ];
    }
}
