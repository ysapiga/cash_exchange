<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'price_to_buy',
        'price_to_sell',
        'position'
    ];

    protected $casts = [
        'price_to_buy' => 'decimal:4',
        'price_to_sell' => 'decimal:4'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
