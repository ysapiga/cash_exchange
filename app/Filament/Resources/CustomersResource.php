<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomersResource\Pages;
use App\Filament\Resources\CustomersResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomersResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Клієнти';

    protected static ?string $modelLabel = 'Клієнт';

    protected static ?string $pluralModelLabel = 'Клієнти';

    /**
     * 'identifier',
     * 'name',
     * 'last_name',
     * 'telephone',
     * 'email',
     * 'date_of_birth',
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('identifier')
                    ->required()
                    ->label('Номер Карти')
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Ім\'я')
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->label('Прізвище')
                    ->maxLength(255),
                Forms\Components\TextInput::make('telephone')
                    ->required()
                    ->label('Телефон')
                    ->tel(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email(),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->label('Дата народження')
                    ->displayFormat('Y-m-d'),
                Forms\Components\Textarea::make('note')
                    ->label('Примітка')
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('identifier')->label('Номер Карти')->sortable()->searchable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('last_name')->label('Прізвище')->sortable()->searchable(),
                TextColumn::make('telephone')->label('Телефон')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('date_of_birth')
                    ->label('Дата народження')
                    ->date('Y-m-d') // тут формат дати
                    ->sortable(),
                TextColumn::make('note')->label('Примітка'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomers::route('/create'),
            'edit' => Pages\EditCustomers::route('/{record}/edit'),
        ];
    }
}
