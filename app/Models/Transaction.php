<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    public const TYPE_BUY = 'buy';                // клієнт купує валюту за гривню

    public const TYPE_SELL = 'sell';              // клієнт продає валюту за гривню

    public const TYPE_CONVERSION = 'conversion';  // валюта → валюта

    public const TYPES = [
        self::TYPE_BUY => 'Купівля',
        self::TYPE_SELL => 'Продаж',
        self::TYPE_CONVERSION => 'Конвертація',
    ];

    protected $fillable = [
        'customer_id',
        'user_id',
        'exchange_point_id',
        'from_currency_id',
        'from_amount',
        'to_currency_id',
        'to_amount',
        'rate',
        'performed_at',
        'note',
    ];

    protected $casts = [
        'from_amount' => 'decimal:2',
        'to_amount' => 'decimal:2',
        'rate' => 'decimal:4',
        'performed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exchangePoint(): BelongsTo
    {
        return $this->belongsTo(ExchangePoint::class);
    }

    public function fromCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    public function toCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }

    /**
     * Тип операції визначається парою валют, окремо не зберігається.
     */
    public function getTypeAttribute(): string
    {
        return match (true) {
            $this->fromCurrency?->currency_code === 'UAH' => self::TYPE_BUY,
            $this->toCurrency?->currency_code === 'UAH' => self::TYPE_SELL,
            default => self::TYPE_CONVERSION,
        };
    }
}
