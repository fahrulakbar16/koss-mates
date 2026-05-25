<?php

namespace App\Http\Requests\Transaksi;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
            'boarding_house_id' => 'nullable|exists:boarding_houses,id',
            'room_id' => 'required|exists:rooms,id',
            'room_price_id' => 'required|exists:rooms_price,id',
            'payment_scheme' => 'required|in:full,installment',
            'total_price' => 'required|numeric',
            'planned_checkin_date' => 'required|date|after_or_equal:today',
        ];
    }
}
