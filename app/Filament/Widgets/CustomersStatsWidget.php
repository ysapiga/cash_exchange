<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomersStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = Customer::where('created_at', '>=', now()->subDay())->count();

        return [
            Stat::make('Нових клієнтів за останню добу', $today)
                ->icon('heroicon-o-user-plus')
                ->color($today > 0 ? 'success' : 'gray'),
        ];
    }
}
