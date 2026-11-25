<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Гривня потрібна як валюта в транзакціях; додаємо лише якщо її ще немає
        if (DB::table('currencies')->where('currency_code', 'UAH')->exists()) {
            return;
        }

        DB::table('currencies')->insert([
            'currency_code' => 'UAH',
            'icon' => '🇺🇦',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Не видаляємо: запис міг існувати до міграції і на нього посилаються транзакції
    }
};
