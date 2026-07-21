<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTenantProfileRequest extends FormRequest
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
        return [
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            'id_card_number' => 'required|string|max:30',
            'file_ktp' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'emergency_contact' => 'nullable|string|max:255',
        ];
    }
}
