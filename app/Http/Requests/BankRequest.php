<?php

namespace App\Http\Requests;

use App\Models\Bank;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('bank');
        $id = $item instanceof Bank ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:15',
                Rule::unique('banks', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode bank wajib diisi.',
            'kode.max' => 'Kode bank maksimal 15 karakter.',
            'kode.unique' => 'Kode bank sudah terdaftar.',
            'nama.required' => 'Nama bank wajib diisi.',
            'nama.max' => 'Nama bank maksimal 100 karakter.',
        ];
    }
}
