<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionLog extends Model
{
    use HasFactory;

    protected $table = 'transaction_logs';

    protected $fillable = [
        'expense_id',
        'transaction_id',
        'room_id',
        'boarding_house_id',
        'type',
        'amount',
        'status',
        'description',
        'payment_method',
        'reference_number',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    /** Filter out payment logs whose underlying payment is no longer successful. */
    public function scopeSuccessful(Builder $query): Builder
    {
        $paymentReference = $query->getConnection()->getDriverName() === 'sqlite'
            ? "'PAY-' || payments.id || '-%'"
            : "CONCAT('PAY-', payments.id, '-%')";

        return $query->where('transaction_logs.status', 'completed')
            ->where(function ($entries) use ($paymentReference) {
                $entries->where('transaction_logs.type', '<>', 'payment')
                    ->orWhereExists(function ($payments) use ($paymentReference) {
                        $payments->selectRaw('1')->from('payments')
                            ->whereColumn('payments.transaction_id', 'transaction_logs.transaction_id')
                            ->where('payments.payment_status', 'success')
                            ->where(function ($reference) use ($paymentReference) {
                                // Legacy imported entries do not have payment references.
                                $reference->whereNull('transaction_logs.reference_number')
                                    ->orWhereRaw("transaction_logs.reference_number LIKE {$paymentReference}");
                            });
                    });
            });
    }

    /**
     * Get the transaction that owns the transaction log
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * Get the room that owns the transaction log
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Get the boarding house that owns the transaction log
     */
    public function boardingHouse(): BelongsTo
    {
        return $this->belongsTo(BoardingHouse::class, 'boarding_house_id');
    }
}
