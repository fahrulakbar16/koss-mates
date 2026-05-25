<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRolePermissionsRequest extends FormRequest
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
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
            'checked' => 'required|boolean',
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
            'permission_ids.required' => 'Permission IDs wajib diisi.',
            'permission_ids.array' => 'Permission IDs harus berupa array.',
            'permission_ids.*.integer' => 'Setiap permission ID harus berupa angka.',
            'permission_ids.*.exists' => 'Permission yang dipilih tidak valid.',
            'checked.required' => 'Status checked wajib diisi.',
            'checked.boolean' => 'Status checked harus berupa boolean.',
        ];
    }
}

