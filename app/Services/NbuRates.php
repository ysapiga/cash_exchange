<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Official National Bank of Ukraine exchange rates (UAH per 1 unit of currency).
 */
class NbuRates
{
    private const URL = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange';

    /**
     * @return array<string, float> currency code => rate, empty if the NBU API is unavailable
     */
    public function today(): array
    {
        $date = now()->format('Ymd');
        $key = "nbu_rates_{$date}";

        if ($rates = Cache::get($key)) {
            return $rates;
        }

        try {
            $rates = Http::timeout(5)
                ->get(self::URL, ['json' => '', 'date' => $date])
                ->throw()
                ->collect()
                ->mapWithKeys(fn (array $row) => [$row['cc'] => (float) $row['rate']])
                ->all();
        } catch (\Throwable $e) {
            Log::warning('NBU rates request failed: '.$e->getMessage());

            return [];
        }

        // Don't cache failures, so the next page load retries.
        if ($rates) {
            Cache::put($key, $rates, now()->addHour());
        }

        return $rates;
    }
}
