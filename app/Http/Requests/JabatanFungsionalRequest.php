<?php

namespace App\Http\Requests;

use App\Models\JabatanFungsional;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JabatanFungsionalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('jabatan_fungsional');
        $id = $item instanceof JabatanFungsional ? $item->id : $item;

        return [
            'klasifikasi_sdm_id' => ['required', 'integer', 'exists:klasifikasi_sdms,id'],
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('jabatan_fungsionals', 'kode')
                    ->where(fn ($q) => $q->where('klasifikasi_sdm_id', $this->input('klasifikasi_sdm_id')))
                    ->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'pangkat_id' => ['nullable', 'integer', 'exists:pangkats,id'],
            'angka_kredit' => ['required', 'integer', 'min:0', 'max:100000'],
            'usia_pensiun' => ['required', 'integer', 'min:1', 'max:100'],
            'referensi_sister' => ['nullable', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'klasifikasi_sdm_id.required' => 'Jabatan akademik wajib dipilih.',
            'klasifikasi_sdm_id.exists' => 'Jabatan akademik tidak valid.',
            'kode.required' => 'Kode jabatan wajib diisi.',
            'kode.max' => 'Kode jabatan maksimal 20 karakter.',
            'kode.unique' => 'Kode jabatan sudah terdaftar pada jabatan akademik tersebut.',
            'nama.required' => 'Nama jabatan fungsional wajib diisi.',
            'nama.max' => 'Nama jabatan fungsional maksimal 150 karakter.',
            'pangkat_id.exists' => 'Golongan/Pangkat tidak valid.',
            'angka_kredit.required' => 'Angka kredit wajib diisi.',
            'angka_kredit.integer' => 'Angka kredit harus berupa angka.',
            'usia_pensiun.required' => 'Usia pensiun wajib diisi.',
            'usia_pensiun.integer' => 'Usia pensiun harus berupa angka.',
        ];
    }
}
