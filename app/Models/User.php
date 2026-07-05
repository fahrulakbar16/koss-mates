<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Owner;

class User extends Authenticatable
{
    use HasRoles;

    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'profile_photo_path',
        'google_id',
        'external_id',
        'password',
        'email_verified_at',
        'active_at',
        'last_seen',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        // Expose a simple primary role string for API resources/UI needs
        'primary_role',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'active_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /**
     * Primary role name from Spatie roles (first assigned role), or null.
     */
    public function getPrimaryRoleAttribute(): ?string
    {
        try {
            return optional($this->roles)->pluck('name')->first();
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Get all transactions for the user
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Boarding houses owned by this user (as Pemilik)
     */
    public function boardingHouses(): HasMany
    {
        return $this->hasMany(BoardingHouse::class, 'owner_id');
    }

    /**
     * Boarding houses managed by this user (as Pengelola)
     */
    public function managedBoardingHouses(): HasMany
    {
        return $this->hasMany(BoardingHouse::class, 'pengelola_id');
    }

    /**
     * Clusters managed by this user (as Pengelola)
     */
    public function managedClusters(): HasMany
    {
        return $this->hasMany(\App\Models\Cluster::class, 'pengelola_id');
    }

    /**
     * Get the tenant details for the user
     */
    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class, 'user_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(UserRooms::class);
    }

    public function roomActive(): HasOne
    {
        return $this->hasOne(UserRooms::class)->latest();
    }

    public function owner(): HasOne
    {
        return $this->hasOne(Owner::class);
    }

    public function aktivasiAkun(): HasOne
    {
        return $this->hasOne(AktivasiAkun::class, 'user_id');
    }
}
