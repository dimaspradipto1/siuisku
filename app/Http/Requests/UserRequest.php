<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $user = $this->route('user');
        $isUpdate = $user !== null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($user?->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'roles' => ['nullable', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => [
                $isUpdate ? 'nullable' : 'required',
                'string',
                'min:6',
                'confirmed',
            ],
            'role_assignments' => ['nullable', 'array'],
            'role_assignments.*.modul_id' => ['required_with:role_assignments', 'exists:moduls,id'],
            'role_assignments.*.role_id' => ['required_with:role_assignments', 'exists:roles,id'],
            'role_assignments.*.unit_kerja_id' => ['nullable', 'exists:unit_kerjas,id'],
            'role_assignments.*.jenis_jabatan_struktural_id' => ['nullable', 'exists:jenis_jabatan_strukturals,id'],
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
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role_assignments.*.modul_id.required_with' => 'Modul wajib dipilih.',
            'role_assignments.*.role_id.required_with' => 'Role hak akses wajib dipilih.',
        ];
    }
}
