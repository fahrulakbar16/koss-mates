<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'boarding_house_id',
        'amount',
        'status',
        'proof',
        'is_verified',
        'transfer_date',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'transfer_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function boardingHouse()
    {
        return $this->belongsTo(BoardingHouse::class);
    }
}
