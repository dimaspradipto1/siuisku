<?php

namespace App\Http\Requests;

use App\Models\PerguruanTinggi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerguruanTinggiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('perguruan_tinggi');
        $id = $item instanceof PerguruanTinggi ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('perguruan_tinggis', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:180'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'no_telp' => ['nullable', 'string', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode perguruan tinggi wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.unique' => 'Kode perguruan tinggi sudah terdaftar.',
            'nama.required' => 'Nama perguruan tinggi wajib diisi.',
            'nama.max' => 'Nama perguruan tinggi maksimal 180 karakter.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',
            'no_telp.max' => 'No telepon maksimal 30 karakter.',
        ];
    }
}
