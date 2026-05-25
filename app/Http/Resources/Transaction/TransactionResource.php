<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_code' => $this->transaction_code,
            'boarding_house' => $this->room->boardingHouse->name,
            'room' => $this->room->name,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'type' => $this->type,
            'paid_installment_amount' => $this->payment_success_sum_amount == "null" ? 0 : (int)  $this->payment_success_sum_amount,
            'created_at' => $this->created_at->format('d M Y'),
        ];
    }
}
