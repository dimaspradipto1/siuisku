<?php

namespace App\Http\Requests;

use App\Models\JamKerja;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JamKerjaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('jam_kerja');
        $id = $item instanceof JamKerja ? $item->id : $item;

        return [
            'nama' => [
                'required',
                'string',
                'max:150',
                Rule::unique('jam_kerjas', 'nama')->ignore($id),
            ],
            'is_normal' => ['nullable', 'boolean'],
            'jam_datang' => ['required', 'date_format:H:i'],
            'jam_pulang' => ['required', 'date_format:H:i'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Jenis jam kerja wajib diisi.',
            'nama.max' => 'Jenis jam kerja maksimal 150 karakter.',
            'nama.unique' => 'Jenis jam kerja sudah terdaftar.',
            'jam_datang.required' => 'Jam datang wajib diisi.',
            'jam_datang.date_format' => 'Format jam datang harus HH:MM (contoh: 08:00).',
            'jam_pulang.required' => 'Jam pulang wajib diisi.',
            'jam_pulang.date_format' => 'Format jam pulang harus HH:MM (contoh: 17:00).',
        ];
    }
}
