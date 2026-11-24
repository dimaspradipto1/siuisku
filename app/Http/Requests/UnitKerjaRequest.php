<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitKerjaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $unit = $this->route('unit_kerja');

        return [
            'kode' => [
                'required',
                'string',
                'max:30',
                'alpha_dash',
                Rule::unique('unit_kerjas', 'kode')->ignore($unit?->id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'jenis' => ['required', 'in:universitas,lembaga,biro,fakultas,prodi,unit'],
            'parent_id' => [
                'nullable',
                'exists:unit_kerjas,id',
                Rule::notIn([$unit?->id]),
            ],
            'urutan' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode.required' => 'Kode unit kerja wajib diisi.',
            'kode.alpha_dash' => 'Kode unit kerja hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).',
            'kode.unique' => 'Kode unit kerja sudah digunakan.',
            'nama.required' => 'Nama unit kerja wajib diisi.',
            'jenis.required' => 'Jenis unit kerja wajib dipilih.',
            'parent_id.not_in' => 'Induk unit kerja tidak boleh memilih dirinya sendiri.',
        ];
    }
}
