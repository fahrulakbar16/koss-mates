<?php

namespace App\Http\Resources\Admin\FinancialReport;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTransaksiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        if (
            $request->routeIs('admin.financial-reports.room-detail') ||
            $request->routeIs('admin.financial-reports.room-detail.print') ||
            $request->routeIs('admin.financial-reports.recap') ||
            $request->routeIs('admin.financial-reports.recap.print') ||
            $request->routeIs('api.admin.financial-reports.recap.print') ||
            $request->routeIs('api.admin.financial-reports.room-detail.print')
        ) {
            $paymentMethod = $this->payment_method;
            if (!$paymentMethod) {
                $payment = $this->payments ? $this->payments->first() : null;
                $paymentMethod = $payment ? ($payment->payment_method === 'gateway' ? 'Transfer (Midtrans)' : 'Tunai') : 'Cash';
            } else {
                $paymentMethod = $paymentMethod === 'gateway' ? 'Transfer (Midtrans)' : ($paymentMethod === 'cash' ? 'Tunai' : $paymentMethod);
            }

            return [
                'id' => $this->id,
                'date' => null,
                'tenant' => $this->transactionLogs?->first()?->transaction?->user?->name ?? '-',
                'room_id' => $this->id,
                'room_name' => $this->name ?? '-',
                'description' => $this->transactionLogs?->first()?->description ?? '-',
                'payment_method' => $paymentMethod,
                'amount' => $this->paid_amount_sum,
                'status' => $this->transactionLogs?->first()?->status ?? '-',
            ];
        }
    }
}
