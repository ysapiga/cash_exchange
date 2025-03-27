<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_name',
        'contact_phone',
        'request_date',
        'is_pending',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'is_pending' => 'boolean',
    ];
}
