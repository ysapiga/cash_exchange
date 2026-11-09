<?php

namespace App\Filament\Widgets;

use App\Models\CurrencyRate;
use App\Services\NbuRates;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class NbuRatesWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    private ?array $nbu = null;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Ваш курс і курс НБУ на '.now()->format('d.m.Y'))
            ->description(empty($this->nbu()) ? 'Не вдалося отримати курс НБУ, спробуйте оновити сторінку пізніше.' : null)
            ->query(CurrencyRate::query()->with('currency')->orderBy('position'))
            ->paginated(false)
            ->columns([
                Tables\Columns\ImageColumn::make('flag')
                    ->label('')
                    ->getStateUsing(fn (CurrencyRate $record) => $record->currency?->flag_url)
                    ->circular()
                    ->size(24),
                Tables\Columns\TextColumn::make('currency.currency_code')
                    ->label('Валюта')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('price_to_buy')
                    ->label('Купівля')
                    ->formatStateUsing(fn ($state) => $this->format((float) $state)),
                Tables\Columns\TextColumn::make('price_to_sell')
                    ->label('Продаж')
                    ->formatStateUsing(fn ($state) => $this->format((float) $state)),
                Tables\Columns\TextColumn::make('nbu')
                    ->label('НБУ')
                    ->getStateUsing(fn (CurrencyRate $record) => $this->nbuRate($record))
                    ->formatStateUsing(fn ($state) => $this->format((float) $state))
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('buy_diff')
                    ->label('Купівля від НБУ')
                    ->getStateUsing(fn (CurrencyRate $record) => $this->diff((float) $record->price_to_buy, $this->nbuRate($record)))
                    // Buying above the official rate is unusual — highlight it.
                    ->color(fn (CurrencyRate $record) => ($nbu = $this->nbuRate($record)) && $record->price_to_buy > $nbu ? 'danger' : 'gray')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('sell_diff')
                    ->label('Продаж від НБУ')
                    ->getStateUsing(fn (CurrencyRate $record) => $this->diff((float) $record->price_to_sell, $this->nbuRate($record)))
                    // Selling below the official rate is unusual — highlight it.
                    ->color(fn (CurrencyRate $record) => ($nbu = $this->nbuRate($record)) && $record->price_to_sell < $nbu ? 'danger' : 'gray')
                    ->placeholder('—'),
            ]);
    }

    private function nbu(): array
    {
        return $this->nbu ??= app(NbuRates::class)->today();
    }

    private function nbuRate(CurrencyRate $record): ?float
    {
        return $this->nbu()[$record->currency?->currency_code] ?? null;
    }

    private function diff(float $ours, ?float $nbu): ?string
    {
        if (! $nbu) {
            return null;
        }

        $delta = $ours - $nbu;
        $sign = $delta > 0 ? '+' : ($delta < 0 ? '−' : '');

        return $sign.$this->format(abs($delta), $nbu).' ('.$sign.number_format(abs($delta) / $nbu * 100, 1).'%)';
    }

    /**
     * Small rates like HUF (≈0.14) need 4 decimals; precision follows $scale when given.
     */
    private function format(float $rate, ?float $scale = null): string
    {
        return number_format($rate, ($scale ?? $rate) < 1 ? 4 : 2, '.', ' ');
    }
}
