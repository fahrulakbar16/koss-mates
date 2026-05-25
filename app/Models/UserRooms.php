<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRooms extends Model
{
    protected $table = 'user_rooms';

    protected $fillable = [
        'user_id',
        'boarding_house_id',
        'room_id',
        'room_price_id',
        'status',
        'start_date',
        'end_date',
        'foto_kamar',
        'verifikasi_admin',
        'planned_checkin_date',
    ];

    protected $casts = [
        'planned_checkin_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function boardingHouse()
    {
        return $this->belongsTo(BoardingHouse::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function plan()
    {
        return $this->belongsTo(RoomPrice::class, 'room_price_id', 'id');
    }

    public function transfers()
    {
        return $this->hasMany(RoomTransfer::class, 'user_room_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_room_id');
    }

    public function transactionLogs()
    {
        return $this->hasManyThrough(TransactionLog::class, Transaction::class, 'user_room_id', 'transaction_id');
    }

    public function rekapHistories()
    {
        return $this->hasMany(RekapHistory::class, 'user_room_id');
    }
}
