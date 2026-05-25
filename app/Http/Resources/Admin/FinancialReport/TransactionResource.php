<?php

namespace App\Http\Resources\Admin\FinancialReport;

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
        // For detail room transaction or recap
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
                'date' => $this->transaction_date ?? $this->created_at,
                'tenant' => $this->transaction?->user?->name ?? $this->user?->name ?? '-',
                'room_id' => $this->room_id,
                'room_name' => $this->room?->name ?? $this->room?->nama_kamar ?? '-',
                'description' => ($this->transaction?->type ?? $this->type) === 'extended' ? 'Sewa Kamar ' . ucfirst(date('F', strtotime($this->transaction_date ?? $this->created_at))) : 'Pembayaran Booking',
                'payment_method' => $paymentMethod,
                'amount' => $this->amount ?? $this->total_price,
                'status' => $this->status,
            ];
        }

        // For show (detailed report)
        return [
            'id' => $this->id,
            'date' => $this->transaction_date ?? $this->updated_at,
            'amount' => $this->amount ?? $this->total_price,
            'description' => 'Pembayaran Sewa' . (($this->transaction?->type ?? $this->type) === 'extended' ? ' (Perpanjangan)' : ''),
            'room' => $this->room,
            'user' => $this->transaction?->user ?? $this->user,
            'transaction_id' => $this->transaction_id ?? $this->id,
        ];
    }
}
