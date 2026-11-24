<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BackupRequest extends FormRequest
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
        if ($this->routeIs('pengaturan.backup.storage.import')) {
            return [
                'storage_file' => ['required', 'file', 'mimes:zip', 'max:102400'],
            ];
        }

        return [
            'sql_file' => ['required', 'file', 'extensions:sql,txt', 'max:51200'],
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
            'sql_file.required' => 'Berkas SQL wajib dipilih.',
            'sql_file.file' => 'Input harus berupa berkas yang valid.',
            'sql_file.extensions' => 'Berkas harus berformat .sql.',
            'sql_file.max' => 'Ukuran berkas SQL maksimal 50MB.',
            'storage_file.required' => 'Berkas ZIP storage wajib dipilih.',
            'storage_file.file' => 'Input harus berupa berkas yang valid.',
            'storage_file.mimes' => 'Berkas harus berformat .zip.',
            'storage_file.max' => 'Ukuran berkas ZIP maksimal 100MB.',
        ];
    }
}
