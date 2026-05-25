<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomPrice extends Model
{
    use HasFactory;

    protected $table = 'rooms_price';

    protected $fillable = [
        'room_id',
        'duration',
        'price',
        'name',
        'addons',
    ];

    protected $casts = [
        'duration' => 'integer',
        'price' => 'decimal:2',
        'addons' => 'array',
    ];

    /**
     * Get the room that owns this price
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
