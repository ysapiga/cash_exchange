<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExchangePointResource\Pages;
use App\Models\ExchangePoint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExchangePointResource extends Resource
{
    protected static ?string $model = ExchangePoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Точки обміну';
    protected static ?string $modelLabel = 'Точка обміну';
    protected static ?string $pluralModelLabel = 'Точки обміну';
    protected static ?int $navigationSort = 100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Назва')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('Адреса')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('coordinates')
                    ->label('Координати')
                    ->required()
                    ->hint('Формат: lat,lng')
                    ->maxLength(255),
                Forms\Components\TextInput::make('telephone')
                    ->label('Телефон')
                    ->required()
                    ->hint('Декілька номерів через кому: +380991234567, +380671234567')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->label('Активна')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Назва')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Адреса')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telephone')
                    ->label('Телефон')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Оновлено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExchangePoints::route('/'),
            'create' => Pages\CreateExchangePoint::route('/create'),
            'edit' => Pages\EditExchangePoint::route('/{record}/edit'),
        ];
    }
}
