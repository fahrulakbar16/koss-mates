<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
    ];

    /**
     * Get all boarding houses in this cluster
     */
    public function boardingHouses(): HasMany
    {
        return $this->hasMany(BoardingHouse::class, 'cluster_id');
    }

    public function rooms()
    {
        return $this->hasManyThrough(Room::class, BoardingHouse::class);
    }
}
