<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HubunganKerjaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $hubunganKerja = $this->route('hubungan_kerja');

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                'alpha_dash',
                Rule::unique('hubungan_kerjas', 'kode')->ignore($hubunganKerja?->id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'status_aktif' => ['nullable', 'boolean'],
            'pegawai_tetap' => ['nullable', 'boolean'],
            'jenis_asn' => ['required', 'in:Non ASN,PNS,PPPK'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode.required' => 'Kode hubungan kerja wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.alpha_dash' => 'Kode hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).',
            'kode.unique' => 'Kode hubungan kerja sudah digunakan.',
            'nama.required' => 'Nama hubungan kerja wajib diisi.',
            'nama.max' => 'Nama hubungan kerja maksimal 150 karakter.',
            'jenis_asn.required' => 'Jenis ASN wajib dipilih.',
            'jenis_asn.in' => 'Pilihan Jenis ASN tidak valid.',
        ];
    }
}
