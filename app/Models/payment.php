<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TransactionLog;

class payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'transaction_id',
        'payment_sequence',
        'amount',
        'payment_method',
        'payment_status',
        'payment_date',
        'proof',
        'snap_token',
        'order_id',
        'gateway_response',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'gateway_response' => 'json',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
