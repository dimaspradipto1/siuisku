<?php

namespace App\Http\Requests;

use App\Models\JenisKenaikanPangkat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisKenaikanPangkatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('jenis_kenaikan_pangkat');
        $id = $item instanceof JenisKenaikanPangkat ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('jenis_kenaikan_pangkats', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jenis kenaikan pangkat wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.unique' => 'Kode jenis kenaikan pangkat sudah terdaftar.',
            'nama.required' => 'Jenis pangkat wajib diisi.',
            'nama.max' => 'Jenis pangkat maksimal 150 karakter.',
        ];
    }
}
