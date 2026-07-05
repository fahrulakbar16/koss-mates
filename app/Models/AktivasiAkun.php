<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AktivasiAkun extends Model
{
    use HasFactory;

    protected $table = 'aktivasi_akuns';

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'id_card_number',
        'gender',
        'birth_date',
        'payment_package',
        'entry_date',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'entry_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
