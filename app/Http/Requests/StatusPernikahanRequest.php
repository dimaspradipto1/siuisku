<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatusPernikahanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Status pernikahan wajib diisi.',
            'nama.max' => 'Status pernikahan maksimal 100 karakter.',
        ];
    }
}
