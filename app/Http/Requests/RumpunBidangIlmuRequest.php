<?php

namespace App\Http\Requests;

use App\Models\RumpunBidangIlmu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RumpunBidangIlmuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('rumpun_bidang_ilmu');
        $id = $item instanceof RumpunBidangIlmu ? $item->id : $item;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('rumpun_bidang_ilmus', 'kode')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:180'],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:rumpun_bidang_ilmus,id',
                $id ? Rule::notIn([$id]) : 'nullable',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode rumpun bidang ilmu wajib diisi.',
            'kode.max' => 'Kode maksimal 20 karakter.',
            'kode.unique' => 'Kode rumpun bidang ilmu sudah terdaftar.',
            'nama.required' => 'Nama bidang ilmu wajib diisi.',
            'nama.max' => 'Nama bidang ilmu maksimal 180 karakter.',
            'parent_id.exists' => 'Parent bidang tidak valid.',
            'parent_id.not_in' => 'Parent bidang tidak boleh merujuk ke dirinya sendiri.',
        ];
    }
}
