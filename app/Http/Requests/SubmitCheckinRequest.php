<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitCheckinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // User must be authenticated (handled by middleware)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'foto_kamar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'foto_kamar.required' => 'Foto kamar harus diupload',
            'foto_kamar.image' => 'File harus berupa gambar',
            'foto_kamar.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto_kamar.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}
