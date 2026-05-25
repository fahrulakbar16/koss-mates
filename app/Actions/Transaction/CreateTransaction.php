<?php

namespace App\Actions\Transaction;

use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\Transaction;
use App\Models\UserRooms;
use App\Helpers\LogActivityHelper;
use Illuminate\Support\Facades\DB;

class CreateTransaction
{
    public function execute($user, $data, $type = Transaction::TYPE_EXTENDED)
    {
        try {
            DB::beginTransaction();

            $roomPrice = RoomPrice::where('id', $data['room_price_id'])->firstOrFail();

            // 2. Create UserRoom assignment
            $userRoom = $user->rooms()->create([
                'boarding_house_id' => $data['boarding_house_id'],
                'room_id' => $data['room_id'],
                'room_price_id' => $data['room_price_id'],
                'status' => 'booked',
                'planned_checkin_date' => $data['planned_checkin_date'],
            ]);

            $transaction = $userRoom->transactions()->create([
                'user_id' => $user->id,
                'room_id' => $data['room_id'],
                'room_price_id' => $data['room_price_id'],
                'total_price' => $roomPrice->price,
                'payment_scheme' => $data['payment_scheme'],
                'type' => $type,
                'status' => Transaction::STATUS_PENDING,
                'planned_checkin_date' => $data['planned_checkin_date'],
            ]);

            $room = Room::where('id', $data['room_id'])->firstOrFail();
            $room->update([
                'status' => Room::STATUS_BOOKED,
            ]);


            DB::commit();

            LogActivityHelper::addToLog('Booking berhasil: ' . $userRoom->room->name, [
                'id' => $transaction->id,
                'user_name' => $user->name,
                'amount' => $transaction->total_price,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $transaction;
    }
}
