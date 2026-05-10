<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationAttachment extends Model
{
    protected $table = 'reservation_attachments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'reservation_id',
        'field_key',
        'label',
        'file_url',
        'file_name',
        'stored_path',
        'uploaded_at',
        'created_at',
        'updated_at',
    ];

    public $timestamps = false;

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }

    public function getStoredPathAttribute($value): ?string
    {
        return $value ?: ($this->attributes['file_url'] ?? null);
    }

    public function getFileNameAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $url = $this->attributes['file_url'] ?? '';

        return $url ? basename(parse_url($url, PHP_URL_PATH)) : null;
    }
}