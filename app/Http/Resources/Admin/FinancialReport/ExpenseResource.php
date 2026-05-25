<?php

namespace App\Http\Resources\Admin\FinancialReport;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            'category' => $this->category ?? $this->type,
            'description' => $this->description,
            'amount' => $this->amount,
            'expense_date' => $this->expense_date ?? $this->transaction_date,
        ];
    }
}
