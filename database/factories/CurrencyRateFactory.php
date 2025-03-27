<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Currency;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CurrencyRate>
 */
class CurrencyRateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'currency_id' => Currency::factory(),
            'price_to_buy' => fake()->randomFloat(2, 1, 100),
            'price_to_sell' => function (array $attributes) {
                return $attributes['price_to_buy'] * 1.02; // 2% націнка на продаж
            },
        ];
    }
}
