

@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Currency Rates --}}
        <div class="bg-[#262b3b] rounded-2xl shadow-2xl overflow-hidden border border-gray-700/40">
            <div class="border-l-4 border-[#5F963B] px-5 md:px-8 py-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Курс обміну валют на сьогодні</h1>
                </div>
            </div>

            <div>
                <table class="w-full">
                    <thead>
                        <tr class="border-y border-gray-700/60 bg-gray-800/30">
                            <th class="text-left py-3 px-4 md:px-8 text-gray-400 font-medium text-sm uppercase tracking-wider">Валюта</th>
                            <th class="text-right py-3 px-4 md:px-8">
                                <span class="inline-flex items-center text-xs font-semibold uppercase bg-emerald-500/10 text-emerald-400 px-2 md:px-3 py-1 rounded-full border border-emerald-500/20 whitespace-nowrap">
                                    Купити
                                </span>
                            </th>
                            <th class="text-right py-3 px-4 md:px-8">
                                <span class="inline-flex items-center text-xs font-semibold uppercase bg-red-500/10 text-red-400 px-2 md:px-3 py-1 rounded-full border border-red-500/20 whitespace-nowrap">
                                    Продати
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rates as $rate)
                        <tr class="border-b border-gray-700/40 hover:bg-[#5F963B]/5 transition-colors duration-150 group">
                            <td class="py-4 px-4 md:px-8">
                                <span class="text-base font-semibold text-white group-hover:text-gray-100 transition-colors">
                                    {{ $rate->currency->icon }} {{ $rate->currency->currency_code }}
                                </span>
                            </td>
                            <td class="py-4 px-4 md:px-8 text-right">
                                <span class="text-lg font-bold text-emerald-400 tabular-nums">
                                    {{ rtrim(rtrim(number_format($rate->price_to_buy, 10, '.', ''), '0'), '.') }}
                                </span>
                            </td>
                            <td class="py-4 px-4 md:px-8 text-right">
                                <span class="text-lg font-bold text-red-400 tabular-nums">
                                    {{ rtrim(rtrim(number_format($rate->price_to_sell, 10, '.', ''), '0'), '.') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Calculator --}}
        <div class="bg-[#262b3b] rounded-2xl shadow-2xl overflow-hidden border border-gray-700/40">
            <div class="border-l-4 border-[#5F963B] px-5 md:px-8 py-5">
                <h2 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Калькулятор</h2>
                <p class="text-gray-400 text-sm mt-1">Розрахуйте суму обміну</p>
            </div>
            <div class="px-5 md:px-8 py-6">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3">
                    {{-- From --}}
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Маю</label>
                        <div class="flex rounded-xl overflow-hidden border border-gray-600/60 focus-within:border-[#5F963B] transition-colors duration-200">
                            <input
                                type="number"
                                id="calc-amount"
                                placeholder="0"
                                min="0"
                                class="flex-1 min-w-0 bg-gray-800/80 text-white text-lg font-semibold px-4 py-3 focus:outline-none placeholder-gray-600 tabular-nums"
                            >
                            <select id="calc-from" class="bg-gray-700/80 text-white font-semibold px-3 py-3 focus:outline-none border-l border-gray-600/60 cursor-pointer hover:bg-gray-700 transition-colors">
                                <option value="UAH">UAH</option>
                                @foreach($rates as $rate)
                                <option value="{{ $rate->currency->currency_code }}">{{ $rate->currency->icon }} {{ $rate->currency->currency_code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Swap --}}
                    <button id="calc-swap" title="Поміняти місцями" class="self-center sm:mb-0 mt-1 w-10 h-10 rounded-xl bg-gray-700/60 hover:bg-[#5F963B]/30 border border-gray-600/60 hover:border-[#5F963B]/50 text-gray-400 hover:text-[#7dc44a] flex items-center justify-center transition-all duration-200 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </button>

                    {{-- To --}}
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Отримаю</label>
                        <div class="flex rounded-xl overflow-hidden border border-gray-600/60 bg-gray-800/40">
                            <input
                                type="text"
                                id="calc-result"
                                readonly
                                placeholder="—"
                                class="flex-1 min-w-0 bg-transparent text-emerald-400 text-lg font-bold px-4 py-3 focus:outline-none placeholder-gray-600 tabular-nums cursor-default"
                            >
                            <select id="calc-to" class="bg-gray-700/80 text-white font-semibold px-3 py-3 focus:outline-none border-l border-gray-600/60 cursor-pointer hover:bg-gray-700 transition-colors">
                                @foreach($rates as $rate)
                                <option value="{{ $rate->currency->currency_code }}">{{ $rate->currency->icon }} {{ $rate->currency->currency_code }}</option>
                                @endforeach
                                <option value="UAH">UAH</option>
                            </select>
                        </div>
                    </div>
                </div>

                <p id="calc-hint" class="text-xs text-gray-500 mt-3"></p>
            </div>
        </div>

        <script>
        (function () {
            @php
                $calcRates = $rates->map(fn($r) => [
                    'code' => $r->currency->currency_code,
                    'buy'  => (float) $r->price_to_buy,
                    'sell' => (float) $r->price_to_sell,
                ])->values();
            @endphp
            var ratesData = @json($calcRates);

            var rateMap = {};
            ratesData.forEach(function (r) { rateMap[r.code] = r; });

            var amountInput  = document.getElementById('calc-amount');
            var resultInput  = document.getElementById('calc-result');
            var fromSelect   = document.getElementById('calc-from');
            var toSelect     = document.getElementById('calc-to');
            var swapBtn      = document.getElementById('calc-swap');
            var hint         = document.getElementById('calc-hint');

            function recalc() {
                var amount = parseFloat(amountInput.value);
                var from   = fromSelect.value;
                var to     = toSelect.value;

                if (!amount || amount <= 0 || from === to) {
                    resultInput.value = '';
                    hint.textContent  = '';
                    return;
                }

                var result = null;
                var hintText = '';

                if (from === 'UAH' && rateMap[to]) {
                    // Buying foreign currency: exchange sells to you at sell rate
                    result   = amount / rateMap[to].sell;
                    hintText = 'Курс продажу: 1 ' + to + ' = ' + rateMap[to].sell.toFixed(4) + ' UAH';
                } else if (to === 'UAH' && rateMap[from]) {
                    // Selling foreign currency: exchange buys from you at buy rate
                    result   = amount * rateMap[from].buy;
                    hintText = 'Курс купівлі: 1 ' + from + ' = ' + rateMap[from].buy.toFixed(4) + ' UAH';
                } else if (rateMap[from] && rateMap[to]) {
                    // Cross-rate via UAH
                    var uah  = amount * rateMap[from].buy;
                    result   = uah / rateMap[to].sell;
                    hintText = 'Крос-курс через UAH';
                }

                if (result !== null) {
                    resultInput.value = result.toFixed(4).replace(/\.?0+$/, '') || '0';
                } else {
                    resultInput.value = '';
                }
                hint.textContent = hintText;
            }

            amountInput.addEventListener('input', recalc);
            fromSelect.addEventListener('change', recalc);
            toSelect.addEventListener('change', recalc);

            swapBtn.addEventListener('click', function () {
                var tmp = fromSelect.value;
                fromSelect.value = toSelect.value;
                toSelect.value   = tmp;

                // Swap amount ↔ result
                var tmpVal = amountInput.value;
                amountInput.value = resultInput.value.replace(/[^\d.]/g, '');
                // Will be recalculated:
                recalc();
            });
        })();
        </script>

        {{-- Conversion Rates --}}
        <div class="bg-[#262b3b] rounded-2xl shadow-2xl overflow-hidden border border-gray-700/40">
            <div class="border-l-4 border-[#5F963B] px-5 md:px-8 py-5">
                <h2 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Курс конвертації валют</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-y border-gray-700/60 bg-gray-800/30">
                            <th class="text-center py-3 px-5 md:px-8 text-gray-400 font-medium text-sm uppercase tracking-wider">Конвертація</th>
                            <th class="text-center py-3 px-5 md:px-8 text-gray-400 font-medium text-sm uppercase tracking-wider">Курс</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($conversionRates as $rate)
                        <tr class="border-b border-gray-700/40 hover:bg-[#5F963B]/5 transition-colors duration-150">
                            <td class="py-4 px-5 md:px-8">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-base font-semibold text-white">{{ $rate->currencyFrom->icon }} {{ $rate->currencyFrom->currency_code }}</span>
                                    <div class="flex items-center gap-0.5 text-[#5F963B]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </div>
                                    <span class="text-base font-semibold text-white">{{ $rate->currencyTo->icon }} {{ $rate->currencyTo->currency_code }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-5 md:px-8 text-center">
                                <span class="text-lg font-bold text-emerald-400 tabular-nums">
                                    {{ rtrim(rtrim(number_format($rate->conversion_rate, 10, '.', ''), '0'), '.') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Map --}}
        @if($exchangePoints->isNotEmpty())
        <div class="bg-[#262b3b] rounded-2xl shadow-2xl overflow-hidden border border-gray-700/40">
            <div class="border-l-4 border-[#5F963B] px-5 md:px-8 py-5">
                <h2 class="text-xl font-bold text-white tracking-tight">Пункти обміну</h2>
                <p class="text-gray-400 text-sm mt-1">Знайдіть нас на карті</p>
            </div>
            <div id="exchange-map" style="height: 420px;"></div>
        </div>
        <script>
            (function () {
                var points = @json($exchangePointsMap);

                var map = L.map('exchange-map');

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    maxZoom: 19,
                }).addTo(map);

                var bounds = [];

                points.forEach(function (point) {
                    var latlng = [point.coords[0], point.coords[1]];
                    bounds.push(latlng);

                    L.marker(latlng)
                        .addTo(map)
                        .bindPopup(
                            '<strong>' + point.name + '</strong><br>' +
                            point.address + '<br>' +
                            '<a href="tel:' + point.telephone + '">' + point.telephone + '</a>'
                        );
                });

                if (bounds.length === 1) {
                    map.setView(bounds[0], 15);
                } else {
                    map.fitBounds(bounds, { padding: [40, 40] });
                }
            })();
        </script>
        @endif

    </div>
@endsection
