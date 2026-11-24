<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisJabatanStrukturalRequest extends FormRequest
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
        $jabatan = $this->route('jenis_jabatan_struktural');

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                'alpha_dash',
                Rule::unique('jenis_jabatan_strukturals', 'kode')->ignore($jabatan?->id),
            ],
            'nama' => ['required', 'string', 'max:150'],
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
            'kode.required' => 'Kode wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.alpha_dash' => 'Kode hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).',
            'kode.unique' => 'Kode jabatan struktural sudah digunakan.',
            'nama.required' => 'Nama jenis jabatan struktural wajib diisi.',
            'nama.max' => 'Nama jenis jabatan struktural maksimal 150 karakter.',
        ];
    }
}
