<?php

namespace App\Filament\Resources\ExchangePointResource\Pages;

use App\Filament\Resources\ExchangePointResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExchangePoint extends EditRecord
{
    protected static string $resource = ExchangePointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
