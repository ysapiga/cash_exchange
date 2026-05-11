<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class NewCustomersChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Нові клієнти за останній місяць';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $days = collect(range(29, 0))->map(fn($daysAgo) => now()->subDays($daysAgo)->startOfDay());

        $counts = Customer::where('created_at', '>=', now()->subDays(30)->startOfDay())
            ->get()
            ->groupBy(fn($c) => Carbon::parse($c->created_at)->format('Y-m-d'))
            ->map->count();

        $labels = $days->map(fn($d) => $d->format('d.m'))->toArray();
        $data   = $days->map(fn($d) => $counts->get($d->format('Y-m-d'), 0))->toArray();

        return [
            'datasets' => [
                [
                    'label'           => 'Нових клієнтів',
                    'data'            => $data,
                    'borderColor'     => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
