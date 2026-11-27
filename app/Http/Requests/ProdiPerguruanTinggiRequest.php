<?php

namespace App\Http\Requests;

use App\Models\ProdiPerguruanTinggi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProdiPerguruanTinggiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('prodi_perguruan_tinggi');
        $id = $item instanceof ProdiPerguruanTinggi ? $item->id : $item;

        return [
            'perguruan_tinggi_id' => ['required', 'integer', 'exists:perguruan_tinggis,id'],
            'jenjang_pendidikan_id' => ['required', 'integer', 'exists:jenjang_pendidikans,id'],
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('prodi_perguruan_tinggis', 'kode')
                    ->where(fn ($q) => $q->where('perguruan_tinggi_id', $this->input('perguruan_tinggi_id')))
                    ->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:180'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'no_telp' => ['nullable', 'string', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'perguruan_tinggi_id.required' => 'Perguruan tinggi wajib dipilih.',
            'perguruan_tinggi_id.exists' => 'Perguruan tinggi tidak valid.',
            'jenjang_pendidikan_id.required' => 'Jenjang pendidikan wajib dipilih.',
            'jenjang_pendidikan_id.exists' => 'Jenjang pendidikan tidak valid.',
            'kode.required' => 'Kode prodi wajib diisi.',
            'kode.max' => 'Kode prodi maksimal 20 karakter.',
            'kode.unique' => 'Kode prodi sudah terdaftar pada perguruan tinggi tersebut.',
            'nama.required' => 'Nama prodi wajib diisi.',
            'nama.max' => 'Nama prodi maksimal 180 karakter.',
        ];
    }
}
