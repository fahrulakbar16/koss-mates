<?php

namespace App\Actions\RoomTransfer;

use App\Models\RoomTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateRoomTransferAction
{
    /**
     * Create a new room transfer request.
     *
     * @param  array  $data
     * @return \App\Models\RoomTransfer
     */
    public function execute(array $data): RoomTransfer
    {
        return RoomTransfer::create([
            'user_room_id' => $data['user_room_id'],
            'user_id' => Auth::user()->id,
            'room_id' => $data['room_id'],
            'room_price_id' => $data['room_price_id'],
            'sisa_pembayaran' => $data['sisa_pembayaran'],
            'kekurangan_pembayaran' => $data['kekurangan_pembayaran'],
            'pengembalian_dana' => $data['pengembalian_dana'],
            'reason' => $data['reason'],
            'plan_date' => $data['plan_date'],
            'status' => 'pending',
        ]);
    }
}
