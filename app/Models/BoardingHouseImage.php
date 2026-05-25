<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoardingHouseImage extends Model
{
    use HasFactory;

    protected $table = 'boarding_house_images';

    protected $fillable = [
        'boarding_house_id',
        'image',
    ];

    /**
     * Get the boarding house that owns the image
     */
    public function boardingHouse(): BelongsTo
    {
        return $this->belongsTo(BoardingHouse::class, 'boarding_house_id');
    }
}
