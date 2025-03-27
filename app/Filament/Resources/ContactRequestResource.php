<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactRequestResource\Pages;
use App\Filament\Resources\ContactRequestResource\RelationManagers;
use App\Models\ContactRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactRequestResource extends Resource
{
    protected static ?string $model = ContactRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';
    
    protected static ?string $navigationLabel = 'Запити';
    
    protected static ?string $modelLabel = 'Запит';
    
    protected static ?string $pluralModelLabel = 'Запити';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('contact_name')
                    ->label('Ім\'я')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_phone')
                    ->label('Телефон')
                    ->required()
                    ->tel()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('request_date')
                    ->label('Дата запиту')
                    ->required(),
                Forms\Components\Toggle::make('is_pending')
                    ->label('В очікуванні')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact_name')
                    ->label('Ім\'я')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_phone')
                    ->label('Телефон')
                    ->searchable(),
                Tables\Columns\TextColumn::make('request_date')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_pending')
                    ->label('Статус')
                    ->boolean()
                    ->trueIcon('heroicon-o-clock')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('warning')
                    ->falseColor('success'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_pending')
                    ->label('Статус')
                    ->placeholder('Всі')
                    ->trueLabel('В очікуванні')
                    ->falseLabel('Виконано'),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('markAsDone')
                    ->label('Позначити як виконаний')
                    ->icon('heroicon-o-check-circle')
                    ->action(function (ContactRequest $record): void {
                        $record->update(['is_pending' => false]);
                    })
                    ->visible(fn (ContactRequest $record): bool => $record->is_pending),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('request_date', 'desc');
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
            'index' => Pages\ListContactRequests::route('/'),
        ];
    }
}
