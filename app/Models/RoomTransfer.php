<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_room_id',
        'room_price_id',
        'room_id',
        'user_id',
        'sisa_pembayaran',
        'kekurangan_pembayaran',
        'pengembalian_dana',
        'reason',
        'plan_date',
        'status',
        'is_process'
    ];

    public function userRoom()
    {
        return $this->belongsTo(UserRooms::class, 'user_room_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function price()
    {
        return $this->belongsTo(RoomPrice::class, 'room_price_id');
    }
}
