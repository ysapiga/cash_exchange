<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_code',
        'icon',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    private const NAMES = [
        'USD' => 'Долар США',
        'EUR' => 'Євро',
        'HUF' => 'Угорський форинт',
        'CZK' => 'Чеська крона',
        'PLN' => 'Польський злотий',
        'GBP' => 'Фунт стерлінгів',
        'CHF' => 'Швейцарський франк',
        'UAH' => 'Українська гривня',
    ];

    private const SYMBOLS = [
        'USD' => '$',
        'EUR' => '€',
        'HUF' => 'Ft',
        'CZK' => 'Kč',
        'PLN' => 'zł',
        'GBP' => '£',
        'CHF' => 'Fr',
        'UAH' => '₴',
    ];

    private const FLAGS = [
        'USD' => 'us',
        'EUR' => 'eu',
        'HUF' => 'hu',
        'CZK' => 'cz',
        'PLN' => 'pl',
        'GBP' => 'gb',
        'CHF' => 'ch',
        'UAH' => 'ua',
    ];

    public function rates()
    {
        return $this->hasMany(CurrencyRate::class);
    }

    public function getNameAttribute(): ?string
    {
        return self::NAMES[$this->currency_code] ?? null;
    }

    public function getSymbolAttribute(): string
    {
        return self::SYMBOLS[$this->currency_code] ?? $this->currency_code;
    }

    public function getFlagUrlAttribute(): ?string
    {
        $country = self::FLAGS[$this->currency_code] ?? null;

        return $country ? asset("images/flags/{$country}.svg") : null;
    }
}
