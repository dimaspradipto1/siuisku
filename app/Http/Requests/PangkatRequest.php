<?php

namespace App\Http\Requests;

use App\Models\Pangkat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PangkatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pangkat = $this->route('pangkat');
        $id = $pangkat instanceof Pangkat ? $pangkat->id : $pangkat;

        return [
            'pangkat' => [
                'required',
                'string',
                'max:20',
                Rule::unique('pangkats', 'pangkat')->ignore($id),
            ],
            'nama_golongan' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'pangkat.required' => 'Pangkat wajib diisi.',
            'pangkat.max' => 'Pangkat maksimal 20 karakter.',
            'pangkat.unique' => 'Pangkat sudah terdaftar.',
            'nama_golongan.required' => 'Nama golongan wajib diisi.',
            'nama_golongan.max' => 'Nama golongan maksimal 150 karakter.',
        ];
    }
}
