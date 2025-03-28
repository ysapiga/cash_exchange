<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversionRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_from',
        'currency_to',
        'conversion_rate',
    ];

    public function currencyFrom()
    {
        return $this->belongsTo(Currency::class, 'currency_from', 'currency_code');
    }

    public function currencyTo()
    {
        return $this->belongsTo(Currency::class, 'currency_to', 'currency_code');
    }
}
