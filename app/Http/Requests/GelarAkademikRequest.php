<?php

namespace App\Http\Requests;

use App\Models\GelarAkademik;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GelarAkademikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('gelar_akademik');
        $id = $item instanceof GelarAkademik ? $item->id : $item;

        return [
            'gelar' => [
                'required',
                'string',
                'max:30',
                Rule::unique('gelar_akademiks', 'gelar')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'gelar.required' => 'Gelar akademik wajib diisi.',
            'gelar.max' => 'Gelar akademik maksimal 30 karakter.',
            'gelar.unique' => 'Gelar akademik sudah terdaftar.',
            'nama.required' => 'Nama gelar wajib diisi.',
            'nama.max' => 'Nama gelar maksimal 150 karakter.',
        ];
    }
}
