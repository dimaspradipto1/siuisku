<?php

namespace App\Http\Requests;

use App\Models\JenisHari;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisHariRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_hari' => ['required', 'string', Rule::in(JenisHari::JENIS_OPTIONS)],
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_hari.required' => 'Jenis hari wajib dipilih.',
            'jenis_hari.in' => 'Jenis hari harus Efektif atau Non Efektif.',
        ];
    }
}
