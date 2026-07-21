<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    protected $table = 'tenants';

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'id_card_number',
        'file_ktp',
        'birth_date',
        'gender',
        'emergency_contact',
        'is_moved',
        'phone_verified_at',
        'tempat_kuliah_kerja',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'phone_verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the tenant details
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all transactions for this tenant
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'tenant_id');
    }

    public function userRoom()
    {
        return $this->hasOne(UserRooms::class, 'tenant_id');
    }
}
