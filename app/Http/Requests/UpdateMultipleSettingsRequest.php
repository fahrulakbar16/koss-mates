<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMultipleSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in controller via Gate
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'settings' => 'required|array',
            'settings.*.key' => 'required|string|max:255',
            'settings.*.value' => 'nullable',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $settings = $this->input('settings', []);
            
            foreach ($settings as $index => $setting) {
                $key = $setting['key'] ?? null;
                
                // Check if it's an image type setting and validate file upload
                if (str_starts_with($key, 'logo_')) {
                    $fileKey = "settings.{$index}.value";
                    
                    if ($this->hasFile($fileKey)) {
                        $file = $this->file($fileKey);
                        
                        // Validate file type
                        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                        if (!in_array($file->getMimeType(), $allowedMimes)) {
                            $validator->errors()->add(
                                $fileKey,
                                'File harus berupa gambar (jpeg, jpg, png, gif, webp).'
                            );
                        }
                        
                        // Validate file size (2MB)
                        if ($file->getSize() > 2048 * 1024) {
                            $validator->errors()->add(
                                $fileKey,
                                'Ukuran file tidak boleh lebih dari 2MB.'
                            );
                        }
                    }
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'settings.required' => 'Data pengaturan wajib diisi.',
            'settings.array' => 'Data pengaturan harus berupa array.',
            'settings.*.key.required' => 'Key pengaturan wajib diisi.',
            'settings.*.key.string' => 'Key pengaturan harus berupa string.',
            'settings.*.key.max' => 'Key pengaturan tidak boleh lebih dari 255 karakter.',
        ];
    }
}

