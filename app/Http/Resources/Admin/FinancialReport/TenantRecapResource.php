<?php

namespace App\Http\Resources\Admin\FinancialReport;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantRecapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $paidAmount = $this->paid_amount ?? 0;
        $paymentStatus = $paidAmount > 0 ? 'Lunas' : 'Belum Bayar';

        return [
            'id' => $this->id,
            'room_id' => $this->room_id,
            'name' => $this->user->name,
            'room_number' => $this->room->name,
            'origin' => $this->user->tenant ? $this->user->tenant->address : '-',
            'phone' => $this->user->tenant ? $this->user->tenant->phone : '-',
            'college' => '-',
            'payment_status' => $paymentStatus,
            'amount' => $paidAmount,
            'start_kos' => $this->created_at,
            'finish_kos' => $this->updated_at,
        ];
    }
}
