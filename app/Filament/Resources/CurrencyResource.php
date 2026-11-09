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

    /** Default flag for each currency code; the flag stays editable in the form. */
    private const FLAGS = [
        'USD' => '🇺🇸',
        'EUR' => '🇪🇺',
        'GBP' => '🇬🇧',
        'CHF' => '🇨🇭',
        'PLN' => '🇵🇱',
        'CZK' => '🇨🇿',
        'HUF' => '🇭🇺',
        'RON' => '🇷🇴',
        'BGN' => '🇧🇬',
        'HRK' => '🇭🇷',
        'RUB' => '🇷🇺',
        'KZT' => '🇰🇿',
        'TRY' => '🇹🇷',
        'CNY' => '🇨🇳',
        'JPY' => '🇯🇵',
        'KRW' => '🇰🇷',
        'AED' => '🇦🇪',
        'ILS' => '🇮🇱',
        'SEK' => '🇸🇪',
        'NOK' => '🇳🇴',
        'DKK' => '🇩🇰',
        'AUD' => '🇦🇺',
        'CAD' => '🇨🇦',
        'NZD' => '🇳🇿',
        'SGD' => '🇸🇬',
        'HKD' => '🇭🇰',
        'THB' => '🇹🇭',
        'INR' => '🇮🇳',
        'BRL' => '🇧🇷',
        'ZAR' => '🇿🇦',
        'MXN' => '🇲🇽',
        'ARS' => '🇦🇷',
        'CLP' => '🇨🇱',
        'PEN' => '🇵🇪',
        'COP' => '🇨🇴',
        'EGP' => '🇪🇬',
        'MAD' => '🇲🇦',
        'TND' => '🇹🇳',
        'LBP' => '🇱🇧',
        'JOD' => '🇯🇴',
        'QAR' => '🇶🇦',
        'KWD' => '🇰🇼',
        'BHD' => '🇧🇭',
        'OMR' => '🇴🇲',
        'SAR' => '🇸🇦',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('currency_code')
                    ->required()
                    ->maxLength(5)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, Forms\Set $set) {
                        $flag = self::FLAGS[strtoupper(trim((string) $state))] ?? null;
                        if ($flag) {
                            $set('icon', $flag);
                        }
                    }),
                Forms\Components\Select::make('icon')
                    ->label('Прапор')
                    ->options(collect(self::FLAGS)->mapWithKeys(fn (string $emoji, string $code) => [$emoji => self::flagLabel($emoji, $code)]))
                    ->allowHtml()
                    ->native(false)
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
                Tables\Columns\ImageColumn::make('icon')
                    ->label('Прапор')
                    ->getStateUsing(fn (Currency $record) => $record->flag_url)
                    ->circular()
                    ->size(28),
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

    private static function flagLabel(string $emoji, string $code): string
    {
        $url = Currency::flagUrlFromEmoji($emoji);
        $img = $url ? '<img src="' . e($url) . '" alt="" style="width:20px;height:20px;border-radius:9999px;object-fit:cover">' : e($emoji);

        return '<span style="display:inline-flex;align-items:center;gap:8px">' . $img . e($code) . '</span>';
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
