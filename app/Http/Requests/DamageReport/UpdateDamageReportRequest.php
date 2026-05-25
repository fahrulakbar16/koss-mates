<?php

namespace App\Http\Requests\DamageReport;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDamageReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Admin authorization handled by middleware/permission
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,in_progress,resolved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
            'repair_cost' => 'nullable|numeric|min:0',
            'repair_proof' => 'nullable|image|max:2048',
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
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid',
            'admin_notes.max' => 'Catatan admin maksimal 1000 karakter',
        ];
    }
}
