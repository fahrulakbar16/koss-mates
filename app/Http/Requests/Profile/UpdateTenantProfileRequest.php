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
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'id_card_number' => 'required|string|max:30',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'emergency_contact' => 'required|string|max:255',
        ];
    }
}
