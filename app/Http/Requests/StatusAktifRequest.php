<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StatusAktifRequest extends FormRequest
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
        $statusAktif = $this->route('status_aktif');

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                'alpha_dash',
                Rule::unique('status_aktifs', 'kode')->ignore($statusAktif?->id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'status_keluar' => ['nullable', 'boolean'],
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
            'kode.required' => 'Kode status aktif wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.alpha_dash' => 'Kode hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).',
            'kode.unique' => 'Kode status aktif sudah digunakan.',
            'nama.required' => 'Nama status aktif wajib diisi.',
            'nama.max' => 'Nama status aktif maksimal 150 karakter.',
        ];
    }
}
