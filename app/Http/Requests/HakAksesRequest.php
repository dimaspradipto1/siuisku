<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HakAksesRequest extends FormRequest
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
        if ($this->routeIs('hak-akses.plotting.store')) {
            return [
                'user_id' => ['required', 'exists:users,id'],
                'modul_id' => ['required', 'exists:moduls,id'],
                'role_id' => ['required', 'exists:roles,id'],
                'unit_kerja_id' => ['nullable', 'exists:unit_kerjas,id'],
                'jenis_jabatan_struktural_id' => ['nullable', 'exists:jenis_jabatan_strukturals,id'],
            ];
        }

        $role = $this->route('hak_akse');

        return [
            'kode' => [
                'required',
                'string',
                'max:40',
                'alpha_dash',
                Rule::unique('roles', 'kode')->ignore($role?->id),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'scope' => ['required', 'in:universitas,unit,self'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
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
            'kode.required' => 'Kode role wajib diisi.',
            'kode.alpha_dash' => 'Kode role hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).',
            'kode.unique' => 'Kode role sudah digunakan.',
            'nama.required' => 'Nama role wajib diisi.',
            'scope.required' => 'Cakupan data (scope OTK) wajib dipilih.',
            'user_id.required' => 'Pengguna wajib dipilih.',
            'modul_id.required' => 'Modul wajib dipilih.',
            'role_id.required' => 'Role hak akses wajib dipilih.',
        ];
    }
}
