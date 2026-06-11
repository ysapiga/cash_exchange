

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
                                    Купівля
                                </span>
                            </th>
                            <th class="text-right py-3 px-4 md:px-8">
                                <span class="inline-flex items-center text-xs font-semibold uppercase bg-red-500/10 text-red-400 px-2 md:px-3 py-1 rounded-full border border-red-500/20 whitespace-nowrap">
                                    Продаж
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
        <div class="bg-[#1e2230] rounded-2xl shadow-2xl border border-gray-700/40">
            <div class="px-5 md:px-8 pt-6 pb-2">
                <h2 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Калькулятор валют</h2>
                <p class="text-gray-400 text-sm mt-1">Розрахуйте суму обміну за поточним курсом</p>
            </div>
            <div class="px-5 md:px-8 pb-6 pt-4">

                {{-- Input row --}}
                <div class="flex flex-col sm:flex-row items-stretch gap-3">
                    {{-- From --}}
                    @php $calcFromDefault = $rates->isNotEmpty() ? $rates->first()->currency->icon . ' ' . $rates->first()->currency->currency_code : '🇺🇦 UAH'; @endphp
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Віддаєте</label>
                        <div class="relative">
                            <div class="flex rounded-xl border border-gray-600/50 bg-[#262b3b] focus-within:border-[#5F963B]/70 transition-colors duration-200 overflow-hidden">
                                <input
                                    type="number"
                                    id="calc-amount"
                                    value="1000"
                                    min="0"
                                    class="flex-1 min-w-0 bg-transparent text-white text-2xl font-bold px-4 py-3.5 focus:outline-none placeholder-gray-600 tabular-nums w-0"
                                >
                                <div class="border-l border-gray-600/50">
                                    <select id="calc-from" class="sr-only"></select>
                                    <button type="button" id="calc-from-btn"
                                            onclick="toggleCalcDropdown('calc-from')"
                                            class="flex items-center gap-1.5 px-3 py-3.5 text-white font-semibold text-sm whitespace-nowrap h-full">
                                        <span id="calc-from-display">{{ $calcFromDefault }}</span>
                                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div id="calc-from-panel" class="hidden absolute right-0 top-full mt-1 bg-[#2d3347] border border-gray-600/50 rounded-xl shadow-2xl min-w-[130px] z-50 overflow-hidden"></div>
                        </div>
                    </div>

                    {{-- To --}}
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Отримуєте</label>
                        <div class="relative">
                            <div class="flex rounded-xl border border-gray-600/50 bg-[#262b3b] overflow-hidden">
                                <input
                                    type="text"
                                    id="calc-result"
                                    readonly
                                    placeholder="—"
                                    class="flex-1 min-w-0 bg-transparent text-white text-2xl font-bold px-4 py-3.5 focus:outline-none placeholder-gray-600 tabular-nums cursor-default w-0"
                                >
                                <div class="border-l border-gray-600/50">
                                    <select id="calc-to" class="sr-only"></select>
                                    <button type="button" id="calc-to-btn"
                                            onclick="toggleCalcDropdown('calc-to')"
                                            class="flex items-center gap-1.5 px-3 py-3.5 text-white font-semibold text-sm whitespace-nowrap h-full">
                                        <span id="calc-to-display">🇺🇦 UAH</span>
                                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div id="calc-to-panel" class="hidden absolute right-0 top-full mt-1 bg-[#2d3347] border border-gray-600/50 rounded-xl shadow-2xl min-w-[130px] z-50 overflow-hidden"></div>
                        </div>
                    </div>
                </div>

                {{-- Rate info bar --}}
                <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 rounded-xl bg-[#262b3b] border border-gray-700/40 px-4 py-3">
                    <div class="flex items-center gap-2 text-sm text-gray-300">
                        <svg class="w-5 h-5 text-[#7dc44a] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span id="calc-rate-text">—</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/></svg>
                        <span id="calc-updated">Оновлено: —</span>
                    </div>
                </div>

            </div>
        </div>

        <script>
        (function () {
            @php
                $calcRates = $rates->map(fn($r) => [
                    'code'    => $r->currency->currency_code,
                    'icon'    => $r->currency->icon,
                    'buy'     => (float) $r->price_to_buy,
                    'sell'    => (float) $r->price_to_sell,
                    'updated' => $r->updated_at ? $r->updated_at->format('d.m.Y H:i') : null,
                ])->values();
                $calcConversions = $conversionRates->map(fn($c) => [
                    'from'     => $c->currencyFrom->currency_code,
                    'fromIcon' => $c->currencyFrom->icon,
                    'to'       => $c->currencyTo->currency_code,
                    'toIcon'   => $c->currencyTo->icon,
                    'rate'     => (float) $c->conversion_rate,
                    'updated'  => $c->updated_at ? $c->updated_at->format('d.m.Y H:i') : null,
                ])->values();
            @endphp
            var ratesData   = @json($calcRates);
            var conversions = @json($calcConversions);

            var icons = { UAH: '🇺🇦' };
            ratesData.forEach(function (r) { icons[r.code] = r.icon; });
            conversions.forEach(function (c) {
                if (!icons[c.from]) icons[c.from] = c.fromIcon;
                if (!icons[c.to])   icons[c.to]   = c.toIcon;
            });

            // targets[from][to] = how to convert: direct pair from the conversion
            // table, or to/from UAH using today's buy/sell prices
            var targets = {};
            function addTarget(from, to, info) {
                (targets[from] = targets[from] || {})[to] = info;
            }
            ratesData.forEach(function (r) {
                addTarget('UAH', r.code, { type: 'fromUah', rate: r.sell, updated: r.updated });
                addTarget(r.code, 'UAH', { type: 'toUah', rate: r.buy, updated: r.updated });
            });
            conversions.forEach(function (c) {
                addTarget(c.from, c.to, { type: 'direct', rate: c.rate, updated: c.updated });
            });

            var fromCodes = Object.keys(targets);

            var amountInput = document.getElementById('calc-amount');
            var resultInput = document.getElementById('calc-result');
            var fromSelect  = document.getElementById('calc-from');
            var toSelect    = document.getElementById('calc-to');
            var rateText    = document.getElementById('calc-rate-text');
            var updatedText = document.getElementById('calc-updated');

            function formatNum(n) {
                if (n === null || isNaN(n)) return '—';
                return new Intl.NumberFormat('uk-UA', { maximumFractionDigits: 4 }).format(n);
            }

            function optionLabel(code) {
                return (icons[code] ? icons[code] + ' ' : '') + code;
            }

            function renderOptions(id, codes, selected) {
                var select = document.getElementById(id);
                var panel  = document.getElementById(id + '-panel');
                select.innerHTML = '';
                panel.innerHTML  = '';
                codes.forEach(function (code) {
                    var opt = document.createElement('option');
                    opt.value = code;
                    opt.textContent = optionLabel(code);
                    opt.selected = code === selected;
                    select.appendChild(opt);

                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'w-full text-left px-4 py-2.5 text-sm text-white hover:bg-[#5F963B]/20 whitespace-nowrap';
                    btn.textContent = optionLabel(code);
                    btn.onclick = function () { selectCalcOption(id, code, optionLabel(code)); };
                    panel.appendChild(btn);
                });
                document.getElementById(id + '-display').textContent = selected ? optionLabel(selected) : '—';
            }

            function refreshToOptions() {
                var codes    = Object.keys(targets[fromSelect.value] || {});
                var selected = codes.indexOf(toSelect.value) !== -1 ? toSelect.value : codes[0];
                renderOptions('calc-to', codes, selected);
            }

            function recalc() {
                var amount = parseFloat(amountInput.value);
                var from   = fromSelect.value;
                var to     = toSelect.value;
                var entry  = (targets[from] || {})[to];

                if (!amount || amount <= 0 || !entry) {
                    resultInput.value  = '';
                    rateText.textContent = '—';
                    updatedText.textContent = 'Оновлено: —';
                    return;
                }

                var result, rateLabel;
                if (entry.type === 'fromUah') {
                    result    = amount / entry.rate;
                    rateLabel = 'Курс: 1 ' + to + ' = ' + entry.rate + ' UAH';
                } else if (entry.type === 'toUah') {
                    result    = amount * entry.rate;
                    rateLabel = 'Курс: 1 ' + from + ' = ' + entry.rate + ' UAH';
                } else {
                    result    = amount * entry.rate;
                    rateLabel = 'Курс: 1 ' + from + ' = ' + entry.rate + ' ' + to;
                }

                resultInput.value       = formatNum(result);
                rateText.textContent    = rateLabel;
                updatedText.textContent = entry.updated ? 'Оновлено: ' + entry.updated : 'Оновлено: —';
            }

            amountInput.addEventListener('input', recalc);
            fromSelect.addEventListener('change', function () { refreshToOptions(); recalc(); });
            toSelect.addEventListener('change', recalc);

            if (fromCodes.length) {
                var defaultFrom = ratesData.length ? ratesData[0].code : fromCodes[0];
                renderOptions('calc-from', fromCodes, defaultFrom);
                refreshToOptions();
                recalc();
            }
        })();

        function toggleCalcDropdown(id) {
            var panel = document.getElementById(id + '-panel');
            ['calc-from', 'calc-to'].forEach(function(p) {
                if (p !== id) document.getElementById(p + '-panel').classList.add('hidden');
            });
            panel.classList.toggle('hidden');
        }

        function selectCalcOption(id, value, display) {
            var select = document.getElementById(id);
            select.value = value;
            document.getElementById(id + '-display').textContent = display;
            document.getElementById(id + '-panel').classList.add('hidden');
            select.dispatchEvent(new Event('change'));
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#calc-from-btn') && !e.target.closest('#calc-from-panel')) {
                document.getElementById('calc-from-panel').classList.add('hidden');
            }
            if (!e.target.closest('#calc-to-btn') && !e.target.closest('#calc-to-panel')) {
                document.getElementById('calc-to-panel').classList.add('hidden');
            }
        });
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
