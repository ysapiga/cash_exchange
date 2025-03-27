<?php

namespace App\Filament\Resources\CurrencyRateResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurrencyRelationManager extends RelationManager
{
    protected static string $relationship = 'currency';

    protected static ?string $recordTitleAttribute = 'currency_code';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('currency_code')
                    ->required()
                    ->maxLength(3)
                    ->label('Код валюти'),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->label('Активна'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('currency_code')
                    ->searchable()
                    ->sortable()
                    ->label('Код валюти'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->label('Активна'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\EditAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\EditBulkAction::make(),
                ]),
            ]);
    }
} 