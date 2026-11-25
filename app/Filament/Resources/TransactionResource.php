<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Transaction;
use App\Support\TransactionRates;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationLabel = 'Транзакції';

    protected static ?string $modelLabel = 'Транзакція';

    protected static ?string $pluralModelLabel = 'Транзакції';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Клієнт')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Клієнт')
                            ->placeholder('Номер карти або телефон')
                            ->required()
                            ->searchable()
                            ->live()
                            ->default(fn () => request()->integer('customer_id') ?: null)
                            ->getSearchResultsUsing(fn (string $search): array => self::searchCustomers($search))
                            ->getOptionLabelUsing(fn ($value): ?string => ($customer = Customer::find($value)) ? self::customerLabel($customer) : null),
                        Forms\Components\Placeholder::make('customer_info')
                            ->hiddenLabel()
                            ->visible(fn (Get $get): bool => filled($get('customer_id')))
                            ->content(fn (Get $get) => self::customerInfo($get('customer_id'))),
                    ]),

                Forms\Components\Section::make('Обмін')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('from_currency_id')
                            ->label('Клієнт віддає')
                            ->options(fn () => Currency::orderBy('currency_code')->pluck('currency_code', 'id'))
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::fillRate($get, $set)),
                        Forms\Components\TextInput::make('from_amount')
                            ->label('Сума')
                            ->numeric()
                            ->minValue(0.01)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::fillToAmount($get, $set)),
                        Forms\Components\Select::make('to_currency_id')
                            ->label('Клієнт отримує')
                            ->options(fn () => Currency::orderBy('currency_code')->pluck('currency_code', 'id'))
                            ->required()
                            ->different('from_currency_id')
                            ->validationMessages(['different' => 'Валюти мають відрізнятися.'])
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::fillRate($get, $set)),
                        Forms\Components\TextInput::make('to_amount')
                            ->label('Сума до видачі')
                            ->helperText('Рахується автоматично, можна змінити вручну')
                            ->numeric()
                            ->minValue(0.01)
                            ->required(),
                        Forms\Components\TextInput::make('rate')
                            ->label('Курс')
                            ->numeric()
                            ->minValue(0.0001)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::fillToAmount($get, $set))
                            ->helperText(fn (Get $get): ?string => self::rateHint($get)),
                    ]),

                Forms\Components\Section::make('Деталі')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('exchange_point_id')
                            ->label('Пункт обміну')
                            ->relationship('exchangePoint', 'name'),
                        Forms\Components\DateTimePicker::make('performed_at')
                            ->label('Дата і час операції')
                            ->seconds(false)
                            ->default(now())
                            ->required(),
                        Forms\Components\Textarea::make('note')
                            ->label('Примітка')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['customer', 'fromCurrency', 'toCurrency', 'user', 'exchangePoint']))
            ->defaultSort('performed_at', 'desc')
            ->columns(self::tableColumns())
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Тип')
                    ->options(Transaction::TYPES)
                    ->query(fn (Builder $query, array $data) => self::applyTypeFilter($query, $data['value'] ?? null)),
                Tables\Filters\SelectFilter::make('currency')
                    ->label('Валюта')
                    ->options(fn () => Currency::orderBy('currency_code')->pluck('currency_code', 'id'))
                    ->query(fn (Builder $query, array $data) => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $q, $id) => $q->where(fn (Builder $q) => $q->where('from_currency_id', $id)->orWhere('to_currency_id', $id))
                    )),
                Tables\Filters\SelectFilter::make('exchange_point_id')
                    ->label('Пункт обміну')
                    ->relationship('exchangePoint', 'name'),
                Tables\Filters\Filter::make('performed_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('З дати'),
                        Forms\Components\DatePicker::make('until')->label('По дату'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('performed_at', '>=', $date))
                        ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('performed_at', '<=', $date))),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    /**
     * Колонки спільні для списку транзакцій і вкладки в картці клієнта.
     */
    public static function tableColumns(bool $withCustomer = true): array
    {
        return array_values(array_filter([
            Tables\Columns\TextColumn::make('performed_at')
                ->label('Дата')
                ->dateTime('d.m.Y H:i')
                ->sortable(),
            $withCustomer ? Tables\Columns\TextColumn::make('customer.identifier')
                ->label('Номер Карти')
                ->searchable() : null,
            $withCustomer ? Tables\Columns\TextColumn::make('customer.full_name')
                ->label('Клієнт')
                ->description(fn (Transaction $record) => $record->customer?->telephone)
                ->searchable(query: fn (Builder $query, string $search) => $query->whereHas(
                    'customer',
                    fn (Builder $q) => $q->where('name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('telephone', 'like', "%{$search}%")
                )) : null,
            Tables\Columns\TextColumn::make('type')
                ->label('Тип')
                ->badge()
                ->formatStateUsing(fn (string $state) => Transaction::TYPES[$state])
                ->color(fn (string $state) => match ($state) {
                    Transaction::TYPE_BUY => 'success',
                    Transaction::TYPE_SELL => 'danger',
                    default => 'info',
                }),
            Tables\Columns\TextColumn::make('from_amount')
                ->label('Віддав')
                ->formatStateUsing(fn (Transaction $record) => self::money($record->from_amount, $record->fromCurrency?->currency_code))
                ->sortable(),
            Tables\Columns\TextColumn::make('to_amount')
                ->label('Отримав')
                ->formatStateUsing(fn (Transaction $record) => self::money($record->to_amount, $record->toCurrency?->currency_code))
                ->sortable(),
            Tables\Columns\TextColumn::make('rate')
                ->label('Курс')
                ->formatStateUsing(fn (Transaction $record) => TransactionRates::label($record->from_currency_id, $record->to_currency_id, (float) $record->rate)),
            Tables\Columns\TextColumn::make('exchangePoint.name')
                ->label('Пункт обміну')
                ->toggleable(),
            Tables\Columns\TextColumn::make('user.name')
                ->label('Оператор')
                ->toggleable(),
            Tables\Columns\TextColumn::make('note')
                ->label('Примітка')
                ->limit(40)
                ->toggleable(isToggledHiddenByDefault: true),
        ]));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    private static function searchCustomers(string $search): array
    {
        $search = trim($search);
        $digits = preg_replace('/\D/', '', $search);

        return Customer::query()
            ->where('identifier', 'like', "%{$search}%")
            ->orWhere('telephone', 'like', "%{$search}%")
            // телефон може бути збережений з пробілами/дужками/дефісами
            ->when(strlen($digits) >= 3, fn (Builder $q) => $q->orWhereRaw(
                "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone, ' ', ''), '-', ''), '(', ''), ')', ''), '+', '') LIKE ?",
                ["%{$digits}%"]
            ))
            ->limit(20)
            ->get()
            ->mapWithKeys(fn (Customer $customer) => [$customer->id => self::customerLabel($customer)])
            ->all();
    }

    private static function customerLabel(Customer $customer): string
    {
        return "{$customer->full_name} · карта {$customer->identifier} · {$customer->telephone}";
    }

    private static function customerInfo($customerId): HtmlString|string
    {
        $customer = Customer::withCount('transactions')->find($customerId);

        if (! $customer) {
            return '';
        }

        $lines = [
            '<strong>'.e($customer->full_name).'</strong>',
            'Телефон: '.e($customer->telephone ?? '—'),
            'Транзакцій раніше: '.$customer->transactions_count,
        ];

        if (filled($customer->note)) {
            $lines[] = 'Примітка: '.e($customer->note);
        }

        return new HtmlString(implode('<br>', $lines));
    }

    private static function fillRate(Get $get, Set $set): void
    {
        $rate = TransactionRates::suggest((int) $get('from_currency_id'), (int) $get('to_currency_id'));

        // Немає курсу для пари — оператор вводить вручну
        $set('rate', $rate);
        self::fillToAmount($get, $set);
    }

    private static function fillToAmount(Get $get, Set $set): void
    {
        $amount = TransactionRates::convert(
            (int) $get('from_currency_id'),
            (int) $get('to_currency_id'),
            is_numeric($get('from_amount')) ? (float) $get('from_amount') : null,
            is_numeric($get('rate')) ? (float) $get('rate') : null,
        );

        if ($amount !== null) {
            $set('to_amount', $amount);
        }
    }

    private static function rateHint(Get $get): ?string
    {
        $fromId = (int) $get('from_currency_id');
        $toId = (int) $get('to_currency_id');

        if (! $fromId || ! $toId || $fromId === $toId) {
            return null;
        }

        $rate = is_numeric($get('rate')) ? (float) $get('rate') : null;

        return TransactionRates::label($fromId, $toId, $rate)
            ?? 'Для цієї пари немає курсу в довіднику — введіть вручну';
    }

    private static function applyTypeFilter(Builder $query, ?string $type): Builder
    {
        $uahId = Currency::where('currency_code', 'UAH')->value('id');

        return match ($type) {
            Transaction::TYPE_BUY => $query->where('from_currency_id', $uahId),
            Transaction::TYPE_SELL => $query->where('to_currency_id', $uahId),
            Transaction::TYPE_CONVERSION => $query->where('from_currency_id', '!=', $uahId)->where('to_currency_id', '!=', $uahId),
            default => $query,
        };
    }

    private static function money($amount, ?string $code): string
    {
        return number_format((float) $amount, 2, '.', ' ').' '.$code;
    }
}
