<?php

namespace App\Http\Requests\RoomPrice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomPriceRequest extends FormRequest
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
            'duration' => ['sometimes', 'integer', 'min:1'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'name' => ['nullable', 'string', 'max:255'],
            'addons' => ['nullable', 'array'],
            'addons.*.name' => ['required', 'string'],
            'addons.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
