<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $table = 'customers';

   protected $fillable = [
    'name',
    'email',
    'phone',
    'address',
    'role',
    'auth_id',
];

    // Supabase handles passwords so we tell Laravel
    // to use the password field from the DB
    protected $hidden = [
        'password',
    ];

}