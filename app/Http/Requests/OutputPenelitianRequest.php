<?php

namespace App\Http\Requests;

use App\Models\OutputPenelitian;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OutputPenelitianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('output_penelitian');
        $id = $item instanceof OutputPenelitian ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('output_penelitians', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode output penelitian wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.unique' => 'Kode output penelitian sudah terdaftar.',
            'nama.required' => 'Output penelitian wajib diisi.',
            'nama.max' => 'Output penelitian maksimal 150 karakter.',
        ];
    }
}
