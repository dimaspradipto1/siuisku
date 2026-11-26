<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisSkRequest extends FormRequest
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
        $jenisSk = $this->route('jenis_sk');

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('jenis_sks', 'kode')->ignore($jenisSk?->id),
            ],
            'nama' => ['required', 'string', 'max:150'],
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
            'kode.required' => 'Kode Jenis SK wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.unique' => 'Kode Jenis SK sudah digunakan.',
            'nama.required' => 'Nama Jenis SK wajib diisi.',
            'nama.max' => 'Nama Jenis SK maksimal 150 karakter.',
        ];
    }
}
