<?php

namespace App\Filament\Resources\CustomersResource\RelationManagers;

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    protected static ?string $title = 'Транзакції';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['fromCurrency', 'toCurrency', 'user', 'exchangePoint']))
            ->defaultSort('performed_at', 'desc')
            ->columns(TransactionResource::tableColumns(withCustomer: false))
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label('Нова транзакція')
                    ->url(fn () => TransactionResource::getUrl('create', ['customer_id' => $this->getOwnerRecord()->getKey()])),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Редагувати')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Transaction $record) => TransactionResource::getUrl('edit', ['record' => $record])),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
