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
        return self::flagUrlFromEmoji($this->icon);
    }

    /**
     * SVG flag for an emoji flag (e.g. 🇺🇸 → images/flags/us.svg).
     */
    public static function flagUrlFromEmoji(?string $emoji): ?string
    {
        $chars = mb_str_split((string) $emoji);
        if (count($chars) !== 2) {
            return null;
        }

        $country = '';
        foreach ($chars as $char) {
            $offset = mb_ord($char) - 0x1F1E6; // Regional indicator symbol letter A
            if ($offset < 0 || $offset > 25) {
                return null;
            }
            $country .= chr(ord('a') + $offset);
        }

        return is_file(public_path("images/flags/{$country}.svg")) ? asset("images/flags/{$country}.svg") : null;
    }
}
