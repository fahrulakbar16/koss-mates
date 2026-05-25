<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_room_id',
        'user_id',
        'title',
        'description',
        'photo',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user room that owns the damage report.
     */
    public function userRoom()
    {
        return $this->belongsTo(UserRooms::class, 'user_room_id');
    }

    /**
     * Get the user that owns the damage report.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the room through user room relationship.
     */
    public function room()
    {
        return $this->hasOneThrough(
            Room::class,
            UserRooms::class,
            'id', // Foreign key on UserRooms table
            'id', // Foreign key on Room table
            'user_room_id', // Local key on DamageReport table
            'room_id' // Local key on UserRooms table
        );
    }
}
