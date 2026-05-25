<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculateRoomTransferCostRequest extends FormRequest
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
            'user_room_id' => 'required|exists:user_rooms,id',
            'room_price_id' => 'required|exists:rooms_price,id',
            'plan_date' => 'required|date|after_or_equal:today',
        ];
    }
}
