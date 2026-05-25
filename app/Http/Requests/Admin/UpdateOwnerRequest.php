<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOwnerRequest extends FormRequest
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
        $userId = $this->route('owner'); // Assuming the route parameter is named 'owner' or getting ID from route

        // If 'owner' is not in route (e.g. if using resource controller showing ID), we might need to adjust.
        // In the controller method: public function update(Request $request, $id)
        // so $this->route('owner') might be null if the parameter is named 'id' or something else.
        // Let's assume standard resource naming or checks.
        // Looking at the controller: public function update(Request $request, $id)
        // So the route parameter is likely 'owner' if using resource, or 'id' if manual.
        // Let's use $this->route('owner') and fallback to $this->route('id') just to be safe,
        // or check common laravel patterns.
        // Actually, in the controller it receives `$id`.
        // Laravel route binding would usually map `{owner}` to `$owner`.
        // Let's grab the ID from the route.

        $id = $this->route('owner') ?: $this->route('id');

        return [
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:8|confirmed',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
        ];
    }
}
