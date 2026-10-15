<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialSource extends Model
{
    protected $fillable = ['transaction_log_id', 'keterangan'];

    public function transactionLog(): BelongsTo
    {
        return $this->belongsTo(TransactionLog::class);
    }
}
