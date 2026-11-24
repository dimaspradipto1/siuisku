<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModulRequest extends FormRequest
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
        $modul = $this->route('modul');

        return [
            'kode' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('moduls', 'kode')->ignore($modul?->id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'singkatan' => ['nullable', 'string', 'max:50'],
            'icon' => ['required', 'string', 'max:80'],
            'warna' => ['required', 'string', 'max:20'],
            'warna_bg' => ['required', 'string', 'max:20'],
            'url' => ['nullable', 'string', 'max:255'],
            'urutan' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
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
            'kode.required' => 'Kode modul wajib diisi.',
            'kode.alpha_dash' => 'Kode modul hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).',
            'kode.unique' => 'Kode modul sudah digunakan.',
            'nama.required' => 'Nama modul wajib diisi.',
            'icon.required' => 'Icon modul wajib diisi.',
            'warna.required' => 'Warna icon wajib diisi.',
            'warna_bg.required' => 'Warna latar icon wajib diisi.',
            'urutan.required' => 'Urutan wajib diisi.',
            'urutan.integer' => 'Urutan harus berupa angka.',
        ];
    }
}
