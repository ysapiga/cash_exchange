<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangePoint extends Model
{
    protected $table = 'exchange_points';

    protected $fillable = [
        'name',
        'address',
        'coordinates',
        'telephone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
