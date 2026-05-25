<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'boarding_house_id',
        'name',
        'number',
        'description',
        'capacity',
        'status',
        'facilities',
    ];

    protected $casts = [
        'facilities' => 'array',
    ];

    protected $appends = ['min_price', 'active_tenant'];

    const STATUS_AVAILABLE = 'available';
    const STATUS_BOOKED = 'booked';
    const STATUS_OCCUPIED = 'occupied';
    const STATUS_MAINTENANCE = 'maintenance';

    /**
     * Get the boarding house that owns the room
     */
    public function boardingHouse(): BelongsTo
    {
        return $this->belongsTo(BoardingHouse::class, 'boarding_house_id');
    }

    public function penyewa(): BelongsToMany
    {
        return $this->belongsToMany(User::class, UserRooms::class, 'room_id', 'user_id')->withPivot('status', 'start_date', 'end_date', 'room_price_id');
    }

    public function penyewaAktif(): BelongsToMany
    {
        return $this->belongsToMany(User::class, UserRooms::class, 'room_id', 'user_id')->wherePivot('status', 'checked_in');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(UserRooms::class, 'room_id');
    }
    /**
     * Get all transactions for this room
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'room_id');
    }

    /**
     * Get all expenses for this room
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'room_id');
    }

    /**
     * Get all transaction logs for this room
     */
    public function transactionLogs(): HasMany
    {
        return $this->hasMany(TransactionLog::class, 'room_id');
    }

    /**
     * Get all prices for this room based on duration
     */
    public function prices(): HasMany
    {
        return $this->hasMany(RoomPrice::class, 'room_id');
    }

    /**
     * Get price for a specific duration
     */
    public function getPriceForDuration(int $duration): ?float
    {
        $roomPrice = $this->prices()->where('duration', $duration)->first();
        return $roomPrice ? (float) $roomPrice->price : null;
    }

    /**
     * Get minimum price from all durations
     */
    public function getMinPrice(): ?float
    {
        $minPrice = $this->prices()->min('price');
        return $minPrice ? (float) $minPrice : null;
    }

    /**
     * Get minimum price attribute (for appends)
     */
    public function getMinPriceAttribute(): ?float
    {
        return $this->getMinPrice();
    }

    /**
     * Get active tenant name (for appends)
     */
    public function getActiveTenantAttribute(): ?string
    {
        // Get the first active tenant from the relationship
        $activeTenant = $this->penyewaAktif->first();
        return $activeTenant ? $activeTenant->name : null;
    }

    public function incomingTransfers()
    {
        return $this->hasMany(RoomTransfer::class, 'room_id');
    }
}
