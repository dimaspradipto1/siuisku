<?php

namespace App\Http\Requests;

use App\Models\JenisTes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisTesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('jenisTes') ?? $this->route('jenis_tes');
        $id = $item instanceof JenisTes ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('jenis_tes', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'nilai_minimal' => ['nullable', 'numeric', 'min:0'],
            'nilai_maksimal' => ['nullable', 'numeric', 'min:0', 'gte:nilai_minimal'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jenis tes wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.unique' => 'Kode jenis tes sudah terdaftar.',
            'nama.required' => 'Nama jenis tes wajib diisi.',
            'nama.max' => 'Nama jenis tes maksimal 150 karakter.',
            'nilai_minimal.numeric' => 'Nilai minimal harus berupa angka.',
            'nilai_maksimal.numeric' => 'Nilai maksimal harus berupa angka.',
            'nilai_maksimal.gte' => 'Nilai maksimal harus lebih besar atau sama dengan nilai minimal.',
        ];
    }
}
