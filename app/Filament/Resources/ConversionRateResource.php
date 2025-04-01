<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConversionRateResource\Pages;
use App\Filament\Resources\ConversionRateResource\RelationManagers;
use App\Models\ConversionRate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConversionRateResource extends Resource
{
    protected static ?string $model = ConversionRate::class;

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Курси Конвертації';

    protected static ?string $modelLabel = 'Курс Конвертації';

    protected static ?string $pluralModelLabel = 'Курси Конвертації';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('currency_from')
                    ->label('Конвертувати з валюти')
                    ->options(
                        \App\Models\Currency::all()->pluck('currency_code', 'currency_code')
                    )
                    ->required(),
                Forms\Components\Select::make('currency_to')
                    ->label('Конвертувати в валюту')
                    ->options(
                        \App\Models\Currency::all()->pluck('currency_code', 'currency_code')
                    )
                    ->required(),
                Forms\Components\TextInput::make('conversion_rate')
                    ->label('Курс конвертації')
                    ->numeric()
                    ->required(),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('currencyFrom.currency_code')
                    ->label('Currency From'),
                Tables\Columns\TextColumn::make('currencyTo.currency_code')
                    ->label('Currency To'),
                Tables\Columns\TextColumn::make('conversion_rate')
                    ->label('Conversion Rate'),
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
            'index' => Pages\ListConversionRates::route('/'),
            'create' => Pages\CreateConversionRate::route('/create'),
            'edit' => Pages\EditConversionRate::route('/{record}/edit'),
        ];
    }
}
