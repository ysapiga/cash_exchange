<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurrencyResource\Pages;
use App\Filament\Resources\CurrencyResource\RelationManagers;
use App\Models\Currency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Доступні Валюти';

    protected static ?string $modelLabel = 'Доступна Валюта';

    protected static ?string $pluralModelLabel = 'Доступні Валюти';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('currency_code')
                    ->required()
                    ->maxLength(5),
                Forms\Components\Select::make('icon')
                    ->options([
                        '🇺🇸' => 'USD',
                        '🇪🇺' => 'EUR',
                        '🇬🇧' => 'GBP',
                        '🇨🇭' => 'CHF',
                        '🇵🇱' => 'PLN',
                        '🇨🇿' => 'CZK',
                        '🇭🇺' => 'HUF',
                        '🇷🇴' => 'RON',
                        '🇧🇬' => 'BGN',
                        '🇭🇷' => 'HRK',
                        '🇷🇺' => 'RUB',
                        '🇰🇿' => 'KZT',
                        '🇹🇷' => 'TRY',
                        '🇨🇳' => 'CNY',
                        '🇯🇵' => 'JPY',
                        '🇰🇷' => 'KRW',
                        '🇦🇪' => 'AED',
                        '🇮🇱' => 'ILS',
                        '🇸🇪' => 'SEK',
                        '🇳🇴' => 'NOK',
                        '🇩🇰' => 'DKK',
                        '🇦🇺' => 'AUD',
                        '🇨🇦' => 'CAD',
                        '🇳🇿' => 'NZD',
                        '🇸🇬' => 'SGD',
                        '🇭🇰' => 'HKD',
                        '🇹🇭' => 'THB',
                        '🇮🇳' => 'INR',
                        '🇧🇷' => 'BRL',
                        '🇿🇦' => 'ZAR',
                        '🇲🇽' => 'MXN',
                        '🇦🇷' => 'ARS',
                        '🇨🇱' => 'CLP',
                        '🇵🇪' => 'PEN',
                        '🇨🇴' => 'COP',
                        '🇪🇬' => 'EGP',
                        '🇲🇦' => 'MAD',
                        '🇹🇳' => 'TND',
                        '🇱🇧' => 'LBP',
                        '🇯🇴' => 'JOD',
                        '🇶🇦' => 'QAR',
                        '🇰🇼' => 'KWD',
                        '🇧🇭' => 'BHD',
                        '🇴🇲' => 'OMR',
                        '🇸🇦' => 'SAR',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Активна')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('currency_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('icon')
                    ->label('Прапор')
                    ->formatStateUsing(fn ($state) => $state)
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
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
            'index' => Pages\ListCurrencies::route('/'),
            'create' => Pages\CreateCurrency::route('/create'),
            'edit' => Pages\EditCurrency::route('/{record}/edit'),
        ];
    }
}
