<?php

namespace App\Support;

use App\Models\ConversionRate;
use App\Models\Currency;
use App\Models\CurrencyRate;

/**
 * Курси для транзакцій — та сама логіка, що й у калькуляторі на головній:
 *  - UAH → валюта: курс продажу (price_to_sell), 1 валюта = rate UAH
 *  - валюта → UAH: курс купівлі (price_to_buy), 1 валюта = rate UAH
 *  - валюта → валюта: лише прямий запис у conversion_rates; rate — скільки
 *    одиниць "меншої" валюти за 1 одиницю "більшої" (більша — дорожча в UAH)
 */
class TransactionRates
{
    public static function suggest(?int $fromId, ?int $toId): ?float
    {
        [$from, $to] = self::currencies($fromId, $toId);

        if (! $from || ! $to || $from->id === $to->id) {
            return null;
        }

        if ($from->currency_code === 'UAH') {
            return self::uahRate($to->id, 'price_to_sell');
        }

        if ($to->currency_code === 'UAH') {
            return self::uahRate($from->id, 'price_to_buy');
        }

        $rate = ConversionRate::where('currency_from', $from->currency_code)
            ->where('currency_to', $to->currency_code)
            ->value('conversion_rate');

        return $rate !== null ? (float) $rate : null;
    }

    /**
     * Сума, яку отримає клієнт, за сумою, яку він віддає, і курсом.
     */
    public static function convert(?int $fromId, ?int $toId, ?float $amount, ?float $rate): ?float
    {
        $multiplier = self::multiplier($fromId, $toId, $rate);

        if ($multiplier === null || $amount === null) {
            return null;
        }

        return round($amount * $multiplier, 2);
    }

    /**
     * Підпис курсу, напр. "1 USD = 41.50 UAH".
     */
    public static function label(?int $fromId, ?int $toId, ?float $rate): ?string
    {
        [$from, $to] = self::currencies($fromId, $toId);

        if (! $from || ! $to || ! $rate) {
            return null;
        }

        if ($from->currency_code === 'UAH') {
            return "1 {$to->currency_code} = {$rate} UAH";
        }

        if ($to->currency_code === 'UAH' || self::fromIsLarger($from, $to)) {
            return "1 {$from->currency_code} = {$rate} {$to->currency_code}";
        }

        return "1 {$to->currency_code} = {$rate} {$from->currency_code}";
    }

    private static function multiplier(?int $fromId, ?int $toId, ?float $rate): ?float
    {
        [$from, $to] = self::currencies($fromId, $toId);

        if (! $from || ! $to || ! $rate) {
            return null;
        }

        if ($from->currency_code === 'UAH') {
            return 1 / $rate;
        }

        if ($to->currency_code === 'UAH' || self::fromIsLarger($from, $to)) {
            return $rate;
        }

        return 1 / $rate;
    }

    private static function fromIsLarger(Currency $from, Currency $to): bool
    {
        return (self::uahRate($from->id, 'price_to_buy') ?? 0) >= (self::uahRate($to->id, 'price_to_buy') ?? 0);
    }

    private static function uahRate(int $currencyId, string $column): ?float
    {
        $rate = CurrencyRate::where('currency_id', $currencyId)->latest('updated_at')->value($column);

        return $rate !== null ? (float) $rate : null;
    }

    /**
     * @return array{0: ?Currency, 1: ?Currency}
     */
    private static function currencies(?int $fromId, ?int $toId): array
    {
        if (! $fromId || ! $toId) {
            return [null, null];
        }

        $currencies = Currency::whereIn('id', [$fromId, $toId])->get()->keyBy('id');

        return [$currencies->get($fromId), $currencies->get($toId)];
    }
}
