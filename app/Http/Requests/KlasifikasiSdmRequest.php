<?php

namespace App\Http\Requests;

use App\Models\KlasifikasiSdm;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KlasifikasiSdmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('klasifikasi_sdm');
        $id = $item instanceof KlasifikasiSdm ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('klasifikasi_sdms', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'jenis_jabatan' => [
                'required',
                'string',
                Rule::in(KlasifikasiSdm::JENIS_JABATAN_OPTIONS),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jabatan akademik wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.unique' => 'Kode jabatan akademik sudah terdaftar.',
            'nama.required' => 'Nama jabatan akademik wajib diisi.',
            'nama.max' => 'Nama jabatan akademik maksimal 150 karakter.',
            'jenis_jabatan.required' => 'Jenis jabatan wajib dipilih.',
            'jenis_jabatan.in' => 'Jenis jabatan tidak valid.',
        ];
    }
}
