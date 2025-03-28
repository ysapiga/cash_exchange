<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurrencyRateResource\Pages;
use App\Filament\Resources\CurrencyRateResource\RelationManagers;
use App\Models\CurrencyRate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurrencyRateResource extends Resource
{
    protected static ?string $model = CurrencyRate::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Курс Валют';

    protected static ?string $modelLabel = 'Курс Валют';

    protected static ?string $pluralModelLabel = 'Курс Валют';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('currency_id')
                    ->relationship('currency', 'currency_code')
                    ->required()
                    ->label('Валюта'),
                Forms\Components\TextInput::make('price_to_buy')
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->label('Ціна купівлі'),
                Forms\Components\TextInput::make('price_to_sell')
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->label('Ціна продажу'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('currency.currency_code')
                    ->searchable()
                    ->label('Валюта'),
                Tables\Columns\TextColumn::make('price_to_buy')
                    ->numeric()
                    ->sortable()
                    ->label('Ціна купівлі'),
                Tables\Columns\TextColumn::make('price_to_sell')
                    ->numeric()
                    ->sortable()
                    ->label('Ціна продажу'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Оновлено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCurrencyRates::route('/'),
            'create' => Pages\CreateCurrencyRate::route('/create'),
            'edit' => Pages\EditCurrencyRate::route('/{record}/edit'),
        ];
    }
}
