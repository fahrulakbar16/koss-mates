<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'user_room_id',
        'room_id',
        'room_price_id',
        'total_price',
        'payment_scheme',
        'type',
        'status',
        'transaction_code',
        'jatuh_tempo',
        'planned_checkin_date',
    ];

    protected $casts = [
        'total_price' => 'integer',
        'payment_scheme' => 'string',
        'status' => 'string',
        'type' => 'string',
        'jatuh_tempo' => 'date',
        'planned_checkin_date' => 'date',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($transaction) {
            $transaction->transaction_code = 'TRX-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5));
        });
    }


    const TYPE_BOOKED = 'booked';
    const TYPE_EXTENDED = 'extended';


    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_INCOMPLETE = 'incomplete';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the user that owns the transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the room that is booked
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Get the user room that is booked
     */
    public function userRoom(): BelongsTo
    {
        return $this->belongsTo(UserRooms::class, 'user_room_id');
    }

    /**
     * Get the specific price selected for this transaction
     */
    public function roomPrice(): BelongsTo
    {
        return $this->belongsTo(RoomPrice::class, 'room_price_id');
    }

    /**
     * Get all payments for this transaction
     */
    public function payments(): HasMany
    {
        return $this->hasMany(payment::class, 'transaction_id');
    }

    /**
     * Get active payment for this transaction (pending status)
     */
    public function paymentActive(): HasOne
    {
        return $this->hasOne(payment::class, 'transaction_id')
            ->where('payment_status', 'pending')
            ->latest();
    }

    /**
     * Get active payment for this transaction (pending status)
     */
    public function paymentSuccess(): HasMany
    {
        return $this->hasMany(payment::class, 'transaction_id')
            ->where('payment_status', 'success');
    }
}
