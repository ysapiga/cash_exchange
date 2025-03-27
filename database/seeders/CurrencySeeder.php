<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Models\CurrencyRate;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Створюємо основні валюти
        $currencies = [
            ['currency_code' => 'USD', 'is_active' => true],
            ['currency_code' => 'EUR', 'is_active' => true],
            ['currency_code' => 'GBP', 'is_active' => true],
            ['currency_code' => 'PLN', 'is_active' => true],
        ];

        foreach ($currencies as $currency) {
            $currencyModel = Currency::create($currency);
            
            // Створюємо курси для кожної валюти
            CurrencyRate::create([
                'currency_id' => $currencyModel->id,
                'price_to_buy' => rand(35, 40),
                'price_to_sell' => rand(41, 45),
            ]);
        }
    }
}
