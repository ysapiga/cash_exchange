<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactInfoResource\Pages;
use App\Filament\Resources\ContactInfoResource\RelationManagers;
use App\Models\ContactInfo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactInfoResource extends Resource
{
    protected static ?string $model = ContactInfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationLabel = 'Контактна Інформація';

    protected static ?int $navigationSort = 100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('address')
                    ->label('Адреса')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('coordinates')
                    ->label('Координати')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telegram')
                    ->label('Telegram')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('instagram')
                    ->label('Instagram')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telephone')
                    ->label('Телефон')
                    ->required()
                    ->tel()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('address')
                    ->label('Адреса')
                    ->searchable(),
                Tables\Columns\TextColumn::make('coordinates')
                    ->label('Координати')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telegram')
                    ->label('Telegram')
                    ->searchable(),
                Tables\Columns\TextColumn::make('instagram')
                    ->label('Instagram')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telephone')
                    ->label('Телефон')
                    ->searchable(),
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
            'index' => Pages\ListContactInfos::route('/'),
            'create' => Pages\CreateContactInfo::route('/create'),
            'edit' => Pages\EditContactInfo::route('/{record}/edit'),
        ];
    }
}
