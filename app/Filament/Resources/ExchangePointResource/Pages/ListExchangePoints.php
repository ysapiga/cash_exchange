<?php

namespace App\Filament\Resources\ExchangePointResource\Pages;

use App\Filament\Resources\ExchangePointResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExchangePoints extends ListRecords
{
    protected static string $resource = ExchangePointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
