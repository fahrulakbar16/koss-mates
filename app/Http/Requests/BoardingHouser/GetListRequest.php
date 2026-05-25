<?php

namespace App\Http\Requests\BoardingHouser;

use Illuminate\Foundation\Http\FormRequest;

class GetListRequest extends FormRequest
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
            'search' => 'nullable|string',
            'cluster_id' => 'nullable|integer|exists:clusters,id',
            'gender' => 'nullable|in:L,P,C',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'limit' => 'nullable|integer',
            'per_page' => 'nullable|integer',
        ];
    }
}
