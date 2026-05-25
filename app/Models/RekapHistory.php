<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapHistory extends Model
{
    protected $table = 'rekap_histories';

    protected $fillable = [
        'user_room_id',
        'month',
        'year',
        'total_price',
        'total_payment',
        'payment_date',
        'status',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'total_price' => 'integer',
        'total_payment' => 'integer',
        'payment_date' => 'date',
    ];

    /**
     * Get the user room that owns the rekap history
     */
    public function userRoom()
    {
        return $this->belongsTo(UserRooms::class, 'user_room_id');
    }
}
