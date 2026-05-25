<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = [
        'user_id',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
