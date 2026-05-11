<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialLinkResource\Pages;
use App\Models\SocialLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SocialLinkResource extends Resource
{
    protected static ?string $model = SocialLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-share';
    protected static ?string $navigationLabel = 'Соціальні мережі';
    protected static ?string $modelLabel = 'Соціальні мережі';
    protected static ?string $pluralModelLabel = 'Соціальні мережі';
    protected static ?int $navigationSort = 110;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('instagram')
                    ->label('Instagram')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telegram')
                    ->label('Telegram')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('facebook')
                    ->label('Facebook')
                    ->url()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('instagram')
                    ->label('Instagram')
                    ->url(fn ($record) => $record->instagram)
                    ->searchable(),
                Tables\Columns\TextColumn::make('telegram')
                    ->label('Telegram')
                    ->url(fn ($record) => $record->telegram)
                    ->searchable(),
                Tables\Columns\TextColumn::make('facebook')
                    ->label('Facebook')
                    ->url(fn ($record) => $record->facebook)
                    ->searchable(),
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
            'index' => Pages\ListSocialLinks::route('/'),
            'create' => Pages\CreateSocialLink::route('/create'),
            'edit' => Pages\EditSocialLink::route('/{record}/edit'),
        ];
    }
}
