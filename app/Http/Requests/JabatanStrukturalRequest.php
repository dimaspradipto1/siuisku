<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JabatanStrukturalRequest extends FormRequest
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
        $jabatan = $this->route('jabatan_struktural');

        return [
            'kode' => [
                'required',
                'string',
                'max:30',
                'alpha_dash',
                Rule::unique('jabatan_strukturals', 'kode')->ignore($jabatan?->id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'jenis_jabatan_struktural_id' => ['nullable', 'exists:jenis_jabatan_strukturals,id'],
            'singkatan' => ['nullable', 'string', 'max:50'],
            'parent_id' => [
                'nullable',
                'exists:jabatan_strukturals,id',
                Rule::notIn([$jabatan?->id]),
            ],
            'unit_kerja_id' => ['nullable', 'exists:unit_kerjas,id'],
            'email' => ['nullable', 'email', 'max:150'],
            'eselon' => ['nullable', 'string', 'max:30'],
            'pangkat_min' => ['nullable', 'string', 'max:50'],
            'pangkat_max' => ['nullable', 'string', 'max:50'],
            'beban_sks' => ['nullable', 'numeric', 'min:0', 'max:99.99'],
            'is_pimpinan' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'urutan' => ['nullable', 'integer', 'min:1'],
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
            'kode.required' => 'Kode jabatan struktural wajib diisi.',
            'kode.alpha_dash' => 'Kode hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).',
            'kode.unique' => 'Kode jabatan struktural sudah digunakan.',
            'nama.required' => 'Nama jabatan struktural wajib diisi.',
            'parent_id.not_in' => 'Parent jabatan tidak boleh memilih dirinya sendiri.',
            'email.email' => 'Format email tidak valid.',
            'beban_sks.numeric' => 'Beban SKS harus berupa angka.',
        ];
    }
}
