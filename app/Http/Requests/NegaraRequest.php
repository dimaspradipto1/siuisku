<?php

namespace App\Http\Requests;

use App\Models\Negara;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NegaraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('negara');
        $id = $item instanceof Negara ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('negaras', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'kode_emis' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode negara wajib diisi.',
            'kode.max' => 'Kode negara maksimal 10 karakter.',
            'kode.unique' => 'Kode negara sudah terdaftar.',
            'nama.required' => 'Nama negara wajib diisi.',
            'nama.max' => 'Nama negara maksimal 150 karakter.',
            'kode_emis.max' => 'Kode Emis maksimal 20 karakter.',
        ];
    }
}
