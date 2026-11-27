<?php

namespace App\Http\Requests;

use App\Models\JenisValidasi;
use App\Models\SettingValidasiStep;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingValidasiStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $jenis = $this->route('settingValidasi');
        $jenisId = $jenis instanceof JenisValidasi ? $jenis->id : $jenis;

        $step = $this->route('step');
        $stepId = $step instanceof SettingValidasiStep ? $step->id : $step;

        return [
            'urutan' => [
                'required',
                'integer',
                'min:1',
                'max:20',
                Rule::unique('setting_validasi_steps', 'urutan')
                    ->where(fn ($query) => $query->where('jenis_validasi_id', $jenisId))
                    ->ignore($stepId),
            ],
            'nama' => ['required', 'string', 'max:120'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'urutan.required' => 'Urutan tahap wajib diisi.',
            'urutan.integer' => 'Urutan tahap harus berupa angka.',
            'urutan.min' => 'Urutan tahap minimal 1.',
            'urutan.max' => 'Urutan tahap maksimal 20.',
            'urutan.unique' => 'Urutan tahap sudah digunakan pada jenis validasi ini.',
            'nama.required' => 'Nama tahap validasi wajib diisi.',
            'nama.max' => 'Nama tahap validasi maksimal 120 karakter.',
            'role_id.required' => 'Role validator wajib dipilih.',
            'role_id.exists' => 'Role validator tidak valid.',
        ];
    }
}
