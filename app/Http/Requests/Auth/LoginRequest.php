<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'user' => ['required', 'string'],
            'password' => ['required', 'string'],
            'device_token' => ['nullable', 'string'],
            'platform' => ['required', 'in:web,android,ios'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'device_token.required' => 'Device token wajib diisi.',
            'platform.required' => 'Platform wajib diisi.',
            'platform.in' => 'Platform tidak valid.',
        ];
    }
}
