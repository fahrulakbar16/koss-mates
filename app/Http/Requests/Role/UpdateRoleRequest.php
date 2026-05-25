<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'leave_quota_per_year' => ['nullable', 'integer', 'min:0'],
            'loan_quota' => ['nullable', 'numeric', 'min:0'],
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
            'name.required' => 'Nama jabatan wajib diisi.',
            'name.string' => 'Nama jabatan harus berupa string.',
            'name.max' => 'Nama jabatan tidak boleh lebih dari 255 karakter.',
            'leave_quota_per_year.integer' => 'Kuota cuti harus berupa angka.',
            'leave_quota_per_year.min' => 'Kuota cuti tidak boleh negatif.',
            'loan_quota.numeric' => 'Kuota pinjaman harus berupa angka.',
            'loan_quota.min' => 'Kuota pinjaman tidak boleh negatif.',
        ];
    }
}

