<?php

namespace App\Http\Requests;

use App\Models\JenisIzin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisIzinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('jenis_izin');
        $id = $item instanceof JenisIzin ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('jenis_izins', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'jenis_kehadiran_id' => ['nullable', 'integer', 'exists:jenis_kehadirans,id'],
            'maksimal_hari' => ['nullable', 'integer', 'min:0', 'max:365'],
            'potong_cuti' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jenis izin wajib diisi.',
            'kode.max' => 'Kode maksimal 10 karakter.',
            'kode.unique' => 'Kode jenis izin sudah terdaftar.',
            'nama.required' => 'Nama jenis izin wajib diisi.',
            'nama.max' => 'Nama jenis izin maksimal 150 karakter.',
            'jenis_kehadiran_id.exists' => 'Status presensi tidak valid.',
            'maksimal_hari.integer' => 'Maksimal hari harus berupa angka.',
            'maksimal_hari.min' => 'Maksimal hari minimal 0.',
        ];
    }
}
