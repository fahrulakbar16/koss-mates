<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoardingHouse extends Model
{
    use HasFactory;

    protected $table = 'boarding_houses';

    protected $fillable = [
        'owner_id',
        'pengelola_id',
        'cluster_id',
        'thumbnail',
        'number',
        'name',
        'description',
        'address',
        'gender',
        'latitude',
        'longitude',
        'persentasi_pemilik',
    ];

    protected $appends = ['thumbnail_url'];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the owner of the boarding house
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function pengelola(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengelola_id');
    }

    /**
     * Get the cluster that owns the boarding house
     */
    public function cluster(): BelongsTo
    {
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }

    public function penyewa(): BelongsToMany
    {
        return $this->belongsToMany(User::class, UserRooms::class, 'boarding_house_id', 'user_id');
    }

    /**
     * Get all rooms in this boarding house
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'boarding_house_id');
    }

    /**
     * Get all rooms in this boarding house
     */
    public function roomsAvailable(): HasMany
    {
        return $this->hasMany(Room::class, 'boarding_house_id')->where('status', 'available');
    }

    /**
     * Get all expenses for this boarding house
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'boarding_house_id');
    }

    /**
     * Get all transactions for this boarding house through rooms
     */
    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, Room::class);
    }


    /**
     * Get all transaction logs for this boarding house
     */
    public function transactionLogs(): HasMany
    {
        return $this->hasMany(TransactionLog::class, 'boarding_house_id');
    }

    /**
     * Get all images for this boarding house
     */
    public function images(): HasMany
    {
        return $this->hasMany(BoardingHouseImage::class, 'boarding_house_id');
    }

    /**
     * Get the thumbnail URL attribute
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return asset('images/placeholder.png');
        }

        if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
            return $this->thumbnail;
        }

        return asset('storage/' . $this->thumbnail);
    }
}
