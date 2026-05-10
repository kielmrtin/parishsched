<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'id';

    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'phone',
        'event_type',
        'reservation_date',
        'reservation_time',
        'status',
        'notes',
        'details',
        'admin_note',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    protected $appends = [
        'preferred_date',
        'preferred_time',
    ];

    public $timestamps = false;

    public function getPreferredDateAttribute()
    {
        return $this->reservation_date;
    }

    public function getPreferredTimeAttribute()
    {
        return $this->reservation_time;
    }

    public function setPreferredDateAttribute($value): void
    {
        $this->attributes['reservation_date'] = $value;
    }

    public function setPreferredTimeAttribute($value)
    {
        $this->attributes['reservation_time'] = $value;
    }

    public function attachments()
    {
        return $this->hasMany(ReservationAttachment::class, 'reservation_id', 'id');
    }
}