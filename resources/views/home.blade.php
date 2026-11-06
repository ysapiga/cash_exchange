@extends('layouts.app')

@section('content')
    @php
        // Rate with at least 2 decimals (3 for rates below 1), keeping any extra precision
        $fmtRate = function ($v) {
            $v = (float) $v;
            $trimmed = rtrim(rtrim(number_format($v, 10, '.', ''), '0'), '.');
            $decimals = str_contains($trimmed, '.') ? strlen(explode('.', $trimmed)[1]) : 0;
            return number_format($v, max($v < 1 ? 3 : 2, $decimals), '.', '');
        };
        $trimRate = fn($v) => rtrim(rtrim(number_format((float) $v, 10, '.', ''), '0'), '.');

        $uahByCode   = $rates->mapWithKeys(fn($r) => [$r->currency->currency_code => (float) $r->price_to_buy]);
        $lastUpdated = $rates->max('updated_at')?->timezone('Europe/Kyiv');
        $featured    = $rates->take(2);
    @endphp

    {{-- Ticker --}}
    @if($rates->isNotEmpty())
    <div class="bg-brand text-ink overflow-hidden whitespace-nowrap" aria-hidden="true">
        <div class="ticker-track inline-flex">
            @for($i = 0; $i < 2; $i++)
            <div class="flex gap-10 py-2.5 pr-10 font-bold text-sm tabular-nums">
                @foreach($rates as $rate)
                <span class="inline-flex items-center gap-2.5">
                    <span class="font-display text-xs">{{ $rate->currency->currency_code }}</span>
                    <span>{{ $fmtRate($rate->price_to_buy) }}</span>
                    <span class="opacity-55">/</span>
                    <span>{{ $fmtRate($rate->price_to_sell) }}</span>
                </span>
                @endforeach
            </div>
            @endfor
        </div>
    </div>
    @endif

    {{-- Hero + calculator --}}
    <section id="top" class="relative overflow-hidden border-b border-[#232A3B]">
        <div aria-hidden="true" class="outline-47 absolute -right-8 -top-10 md:-right-10 md:-top-28 font-display font-extrabold text-[300px] md:text-[620px] leading-none tracking-[-0.06em] pointer-events-none select-none">47</div>

        <div class="relative max-w-[1200px] mx-auto px-4 md:px-6 pt-9 pb-10 md:pt-[72px] md:pb-20 flex flex-wrap gap-10 md:gap-14 items-center">

            <div class="flex-[1_1_480px] min-w-0 flex flex-col gap-5 md:gap-7">
                @if($lastUpdated)
                <span class="inline-flex self-start items-center gap-2 px-3.5 py-2 border border-[#2E3650] rounded-full text-xs md:text-[13px] font-bold text-soft">
                    <span class="w-2 h-2 rounded-full bg-lime"></span>
                    Оновлено {{ $lastUpdated->format('d.m.Y') }} о {{ $lastUpdated->format('H:i') }}
                </span>
                @endif
                <h1 class="font-display font-extrabold text-[30px] sm:text-[40px] md:text-[48px] leading-[1.05] tracking-[-0.035em]">
                    Інформаційний портал<br><span class="text-lime">курсів обміну валют</span>
                </h1>
                <p class="max-w-[480px] text-base md:text-[19px] leading-relaxed text-muted">
                    Курси купівлі та продажу на сьогодні, прямі конвертації між валютами
                    @if($exchangePoints->isNotEmpty()) і {{ $exchangePoints->count() }} {{ trans_choice('пункт|пункти|пунктів', $exchangePoints->count(), [], 'uk') }} обміну в місті@endif.
                    Порахуйте суму онлайн і приходьте.
                </p>

                @if($featured->isNotEmpty())
                <div class="hidden sm:grid grid-cols-2 gap-4 max-w-[520px]">
                    @foreach($featured as $rate)
                    <div class="bg-surface border border-line rounded-[20px] px-[22px] py-5 flex flex-col gap-3.5">
                        <div class="flex items-center gap-2.5">
                            <span class="w-[34px] h-[34px] rounded-full bg-lime text-ink inline-flex items-center justify-center font-extrabold text-sm">{{ $rate->currency->symbol }}</span>
                            <span class="font-display font-bold">{{ $rate->currency->currency_code }}</span>
                        </div>
                        <div class="flex gap-5 tabular-nums">
                            <div>
                                <div class="text-xs font-bold uppercase tracking-[0.08em] text-muted">Купівля</div>
                                <div class="font-display font-bold text-[28px] text-up">{{ $fmtRate($rate->price_to_buy) }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-bold uppercase tracking-[0.08em] text-muted">Продаж</div>
                                <div class="font-display font-bold text-[28px]">{{ $fmtRate($rate->price_to_sell) }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Calculator --}}
            @php $calcFromDefault = $rates->isNotEmpty() ? $rates->first()->currency->currency_code : 'UAH'; @endphp
            <div id="calc" class="flex-[1_1_440px] min-w-0 bg-paper text-ink rounded-3xl md:rounded-[28px] p-5 md:p-8 flex flex-col gap-4 md:gap-5 shadow-[0_30px_80px_rgba(0,0,0,0.45)] scroll-mt-24">
                <div>
                    <h2 class="font-display font-bold text-lg md:text-2xl tracking-tight">Калькулятор валют</h2>
                    <p class="text-paper-muted text-sm mt-1">Розрахуйте суму обміну за поточним курсом</p>
                </div>

                {{-- From --}}
                <div>
                    <label for="calc-amount" class="block text-xs md:text-[13px] font-extrabold uppercase tracking-[0.08em] text-paper-muted mb-2">Віддаєте</label>
                    <div class="relative">
                        <div class="flex items-stretch rounded-2xl md:rounded-[18px] bg-white border-2 border-[#D5DACE] focus-within:border-lime transition-colors min-h-[60px] md:min-h-[72px]">
                            <input type="text" inputmode="decimal" id="calc-amount" value="1000"
                                   class="flex-1 min-w-0 w-0 bg-transparent font-display font-bold text-[26px] md:text-[32px] text-ink px-4 md:px-5 focus:outline-none tabular-nums">
                            <select id="calc-from" class="sr-only" tabindex="-1" aria-hidden="true"></select>
                            <button type="button" id="calc-from-btn" onclick="toggleCalcDropdown('calc-from')" aria-haspopup="listbox" aria-label="Валюта, яку віддаєте"
                                    class="flex items-center gap-1.5 px-4 font-display font-bold text-[15px] md:text-lg text-paper-muted hover:text-ink whitespace-nowrap">
                                <span id="calc-from-display">{{ $calcFromDefault }}</span>
                                <svg aria-hidden="true" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                        <div id="calc-from-panel" class="hidden absolute right-0 top-full mt-1.5 bg-white border border-paper-line rounded-2xl shadow-2xl min-w-[140px] max-h-72 overflow-y-auto z-50 py-1"></div>
                    </div>
                </div>

                {{-- Swap --}}
                <div class="flex justify-center -my-2 md:-my-3 relative z-10">
                    <button type="button" id="calc-swap" aria-label="Поміняти валюти місцями"
                            class="w-11 h-11 rounded-full bg-ink text-lime border-4 border-paper inline-flex items-center justify-center hover:rotate-180 transition-transform duration-300">
                        <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M7 4v16M7 20l-4-4M7 20l4-4M17 20V4M17 4l-4 4M17 4l4 4"/></svg>
                    </button>
                </div>

                {{-- To --}}
                <div class="bg-ink text-white rounded-2xl md:rounded-[18px] px-4 md:px-5 pt-4 pb-4 md:pt-5 flex flex-col gap-1.5">
                    <label for="calc-result" class="text-xs md:text-[13px] font-extrabold uppercase tracking-[0.08em] text-muted">Отримуєте</label>
                    <div class="relative">
                        <div class="flex items-center">
                            <input type="text" inputmode="decimal" id="calc-result" placeholder="—"
                                   class="flex-1 min-w-0 w-0 bg-transparent font-display font-extrabold text-[32px] md:text-[40px] tracking-tight text-up placeholder-[#4B5363] focus:outline-none tabular-nums">
                            <select id="calc-to" class="sr-only" tabindex="-1" aria-hidden="true"></select>
                            <button type="button" id="calc-to-btn" onclick="toggleCalcDropdown('calc-to')" aria-haspopup="listbox" aria-label="Валюта, яку отримуєте"
                                    class="flex items-center gap-1.5 pl-3 font-display font-bold text-[15px] md:text-lg text-white whitespace-nowrap min-h-11">
                                <span id="calc-to-display">UAH</span>
                                <svg aria-hidden="true" class="w-4 h-4 shrink-0 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                        <div id="calc-to-panel" class="hidden absolute right-0 top-full mt-1.5 bg-white text-ink border border-paper-line rounded-2xl shadow-2xl min-w-[140px] max-h-72 overflow-y-auto z-50 py-1"></div>
                    </div>
                    <div class="flex flex-wrap justify-between gap-x-4 gap-y-1 text-[13px] md:text-sm text-muted tabular-nums">
                        <span id="calc-rate-text">—</span>
                        <span id="calc-updated">Оновлено: —</span>
                    </div>
                </div>

                <button type="button" onclick="openModal()" class="min-h-14 rounded-2xl bg-brand hover:bg-[#6faa45] text-white font-extrabold active:scale-[0.98] transition-all">
                    Замовити дзвінок — ми передзвонимо
                </button>
            </div>
        </div>
    </section>

    <script>
    (function () {
        @php
            $calcRates = $rates->map(fn($r) => [
                'code'    => $r->currency->currency_code,
                'buy'     => (float) $r->price_to_buy,
                'sell'    => (float) $r->price_to_sell,
                'updated' => $r->updated_at ? $r->updated_at->timezone('Europe/Kyiv')->format('d.m.Y H:i') : null,
            ])->values();
            $calcConversions = $conversionRates->map(fn($c) => [
                'from'    => $c->currencyFrom->currency_code,
                'to'      => $c->currencyTo->currency_code,
                'rate'    => (float) $c->conversion_rate,
                'updated' => $c->updated_at ? $c->updated_at->timezone('Europe/Kyiv')->format('d.m.Y H:i') : null,
            ])->values();
        @endphp
        var ratesData   = @json($calcRates);
        var conversions = @json($calcConversions);

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

        // UAH value of each currency (buy price), used to decide which
        // currency in a direct pair is the "larger" one
        var uahRate = {};
        ratesData.forEach(function (r) { uahRate[r.code] = r.buy; });

        var fromCodes = Object.keys(targets);

        var amountInput = document.getElementById('calc-amount');
        var resultInput = document.getElementById('calc-result');
        var fromSelect  = document.getElementById('calc-from');
        var toSelect    = document.getElementById('calc-to');
        var rateText    = document.getElementById('calc-rate-text');
        var updatedText = document.getElementById('calc-updated');

        function formatNum(n) {
            if (n === null || isNaN(n)) return '—';
            return new Intl.NumberFormat('uk-UA', { maximumFractionDigits: 2 }).format(n);
        }

        // Parse a user-typed amount, tolerating thousands spaces and comma decimals
        function parseVal(str) {
            if (!str) return NaN;
            str = String(str).replace(/\s/g, '').replace(',', '.').replace(/[^0-9.]/g, '');
            return parseFloat(str);
        }

        function renderOptions(id, codes, selected) {
            var select = document.getElementById(id);
            var panel  = document.getElementById(id + '-panel');
            select.innerHTML = '';
            panel.innerHTML  = '';
            codes.forEach(function (code) {
                var opt = document.createElement('option');
                opt.value = code;
                opt.textContent = code;
                opt.selected = code === selected;
                select.appendChild(opt);

                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'w-full text-left px-4 py-2.5 font-display font-bold text-sm text-ink hover:bg-paper whitespace-nowrap' + (code === selected ? ' text-forest' : '');
                btn.textContent = code;
                btn.onclick = function () { selectCalcOption(id, code, code); };
                panel.appendChild(btn);
            });
            document.getElementById(id + '-display').textContent = selected || '—';
        }

        function refreshToOptions() {
            var codes    = Object.keys(targets[fromSelect.value] || {});
            var selected = codes.indexOf(toSelect.value) !== -1 ? toSelect.value : codes[0];
            renderOptions('calc-to', codes, selected);
        }

        // Resolve the conversion between two currencies into a single
        // multiplier `m` such that:  received = given * m  (given = received / m)
        function getConversion(from, to) {
            var entry = (targets[from] || {})[to];
            if (!entry) return null;

            var m, rateLabel;
            if (entry.type === 'fromUah') {
                m         = 1 / entry.rate;
                rateLabel = 'Курс: 1 ' + to + ' = ' + entry.rate + ' UAH';
            } else if (entry.type === 'toUah') {
                m         = entry.rate;
                rateLabel = 'Курс: 1 ' + from + ' = ' + entry.rate + ' UAH';
            } else {
                // Rate is stored as: how many units of the smaller currency
                // equal 1 unit of the larger one. Larger = higher UAH value.
                var fromIsLarger = (uahRate[from] || 0) >= (uahRate[to] || 0);
                if (fromIsLarger) {
                    m         = entry.rate;
                    rateLabel = 'Курс: 1 ' + from + ' = ' + entry.rate + ' ' + to;
                } else {
                    m         = 1 / entry.rate;
                    rateLabel = 'Курс: 1 ' + to + ' = ' + entry.rate + ' ' + from;
                }
            }
            return { m: m, rateLabel: rateLabel, updated: entry.updated };
        }

        // Which field the user typed in last decides the calculation direction
        var lastEdited = 'amount';

        function recalc() {
            var conv = getConversion(fromSelect.value, toSelect.value);

            if (!conv) {
                (lastEdited === 'amount' ? resultInput : amountInput).value = '';
                rateText.textContent    = '—';
                updatedText.textContent = 'Оновлено: —';
                return;
            }

            rateText.textContent    = conv.rateLabel;
            updatedText.textContent = conv.updated ? 'Оновлено: ' + conv.updated : 'Оновлено: —';

            if (lastEdited === 'result') {
                var received = parseVal(resultInput.value);
                amountInput.value = (received > 0) ? formatNum(received / conv.m) : '';
            } else {
                var given = parseVal(amountInput.value);
                resultInput.value = (given > 0) ? formatNum(given * conv.m) : '';
            }
        }

        amountInput.addEventListener('input', function () { lastEdited = 'amount'; recalc(); });
        resultInput.addEventListener('input', function () { lastEdited = 'result'; recalc(); });
        fromSelect.addEventListener('change', function () { refreshToOptions(); recalc(); });
        toSelect.addEventListener('change', function () { refreshToOptions(); recalc(); });

        // Swap currencies when the reverse pair exists
        document.getElementById('calc-swap').addEventListener('click', function () {
            var from = fromSelect.value, to = toSelect.value;
            if (!(targets[to] || {})[from]) return;
            renderOptions('calc-from', fromCodes, to);
            toSelect.value = from;
            refreshToOptions();
            recalc();
        });

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

    {{-- Currency rates --}}
    <section id="rates" class="max-w-[1200px] mx-auto px-4 md:px-6 pt-12 md:pt-24 scroll-mt-20">
        <div class="flex flex-wrap justify-between items-end gap-4 md:gap-6 mb-5 md:mb-8">
            <div>
                <div class="text-xs md:text-[13px] font-extrabold uppercase tracking-[0.12em] text-lime mb-2 md:mb-3">Курси на сьогодні</div>
                <h2 class="font-display font-bold text-[28px] md:text-[44px] tracking-[-0.03em]">Купівля і продаж</h2>
            </div>
            <p class="text-muted text-[15px] max-w-[340px]">Купівля — за скільки ми купуємо валюту у вас. Продаж — за скільки продаємо вам.</p>
        </div>

        <div class="bg-surface border border-line rounded-[20px] md:rounded-3xl overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b border-line">
                        <th scope="col" class="text-left py-3 md:py-[18px] px-4 md:px-8 text-[11px] md:text-xs font-extrabold uppercase tracking-[0.1em] text-muted">Валюта</th>
                        <th scope="col" class="text-right py-3 md:py-[18px] px-2 md:px-8 text-[11px] md:text-xs font-extrabold uppercase tracking-[0.1em] text-up">Купівля</th>
                        <th scope="col" class="text-right py-3 md:py-[18px] px-4 md:px-8 text-[11px] md:text-xs font-extrabold uppercase tracking-[0.1em] text-muted">Продаж</th>
                        <th scope="col" class="hidden md:table-cell text-right py-[18px] px-8 text-xs font-extrabold uppercase tracking-[0.1em] text-muted">Спред</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rates as $rate)
                    <tr class="border-b border-[#222839] last:border-b-0 hover:bg-[#1C2233] transition-colors">
                        <th scope="row" class="text-left py-3.5 md:py-[18px] px-4 md:px-8 font-semibold">
                            <div class="flex items-center gap-2.5 md:gap-4">
                                <span class="w-[34px] h-[34px] md:w-11 md:h-11 rounded-full bg-[#232A3D] border border-[#323A52] text-lime inline-flex items-center justify-center font-extrabold text-[13px] md:text-[15px] shrink-0">{{ $rate->currency->symbol }}</span>
                                <span class="flex flex-col gap-0.5">
                                    <span class="font-display font-bold text-sm md:text-[17px] text-white">{{ $rate->currency->currency_code }}</span>
                                    @if($rate->currency->name)
                                    <span class="hidden md:block text-sm text-muted font-medium">{{ $rate->currency->name }}</span>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <td class="text-right py-3.5 md:py-[18px] px-2 md:px-8 font-display font-bold text-base md:text-2xl text-up tabular-nums">{{ $fmtRate($rate->price_to_buy) }}</td>
                        <td class="text-right py-3.5 md:py-[18px] px-4 md:px-8 font-display font-bold text-base md:text-2xl text-white tabular-nums">{{ $fmtRate($rate->price_to_sell) }}</td>
                        <td class="hidden md:table-cell text-right py-[18px] px-8 text-[15px] text-muted font-semibold tabular-nums">
                            @if($rate->price_to_buy > 0)
                                {{ number_format(($rate->price_to_sell - $rate->price_to_buy) / $rate->price_to_buy * 100, 1, '.', '') }}%
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- Conversion rates --}}
    @if($conversionRates->isNotEmpty())
    <section id="conv" class="bg-paper text-ink mt-12 md:mt-[72px] scroll-mt-20">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6 py-11 md:py-24">
            <div class="flex flex-wrap justify-between items-end gap-4 md:gap-6 mb-5 md:mb-10">
                <div>
                    <div class="text-xs md:text-[13px] font-extrabold uppercase tracking-[0.12em] text-forest mb-2 md:mb-3">Без гривні посередині</div>
                    <h2 class="font-display font-bold text-[28px] md:text-[44px] tracking-[-0.03em]">Курс конвертації валют</h2>
                </div>
                <p class="text-paper-muted text-[15px] max-w-[360px]">Міняйте одну валюту на іншу напряму. Під кожною парою — як читати курс.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-[repeat(auto-fill,minmax(210px,1fr))] gap-2.5 md:gap-4">
                @foreach($conversionRates as $rate)
                    @php
                        $from = $rate->currencyFrom->currency_code;
                        $to   = $rate->currencyTo->currency_code;
                        $fromIsLarger = ($uahByCode[$from] ?? 0) >= ($uahByCode[$to] ?? 0);
                        $big   = $fromIsLarger ? $from : $to;
                        $small = $fromIsLarger ? $to : $from;
                        $value = $trimRate($rate->conversion_rate);
                    @endphp
                    <div class="bg-white border border-paper-line rounded-2xl md:rounded-[20px] p-3.5 md:p-[22px] flex flex-col gap-2 md:gap-4">
                        <div class="flex items-center gap-1.5 md:gap-2.5 font-display font-bold text-xs md:text-[15px]">
                            <span>{{ $from }}</span>
                            <svg aria-hidden="true" class="w-4 h-4 md:w-5 md:h-5 text-forest" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            <span>{{ $to }}</span>
                        </div>
                        <div class="font-display font-extrabold text-2xl md:text-[34px] tracking-tight tabular-nums">{{ $value }}</div>
                        <div class="text-xs md:text-[13px] text-paper-muted font-semibold tabular-nums md:pt-3 md:border-t md:border-[#E6EAE0]">1 {{ $big }} = {{ $value }} {{ $small }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Exchange points --}}
    @if($exchangePoints->isNotEmpty())
    <section id="points" class="max-w-[1200px] mx-auto px-4 md:px-6 py-11 md:py-24 scroll-mt-20">
        <div class="mb-5 md:mb-10">
            <div class="text-xs md:text-[13px] font-extrabold uppercase tracking-[0.12em] text-lime mb-2 md:mb-3">Мукачево</div>
            <h2 class="font-display font-bold text-[28px] md:text-[44px] tracking-[-0.03em]">Пункти обміну</h2>
        </div>

        <div class="flex flex-wrap gap-6">
            <div class="flex-[1_1_360px] min-w-0 flex flex-col gap-3 md:gap-4">
                @foreach($exchangePoints as $point)
                <div class="bg-surface border border-line rounded-[18px] md:rounded-[20px] p-4 md:px-6 md:py-[22px] flex gap-3.5 md:gap-[18px] items-start">
                    <span class="shrink-0 w-10 h-10 md:w-11 md:h-11 rounded-[12px] md:rounded-[14px] bg-lime text-ink inline-flex items-center justify-center font-display font-extrabold md:text-lg">{{ $loop->iteration }}</span>
                    <div class="flex flex-col gap-1.5 min-w-0">
                        <div class="font-display font-bold text-sm md:text-[17px]">{{ $point->address }}</div>
                        @if($point->name)
                        <div class="text-sm text-muted">{{ $point->name }}</div>
                        @endif
                        <div class="flex flex-wrap gap-x-[18px] gap-y-1 mt-1 md:mt-2">
                            @foreach(array_map('trim', explode(',', $point->telephone)) as $phone)
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="text-lime hover:text-[#A6E07A] font-bold text-sm md:text-[15px] tabular-nums">{{ $phone }}</a>
                            @endforeach
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode(str_replace(' ', '', $point->coordinates)) }}" target="_blank" rel="noopener" class="text-white hover:text-lime font-bold text-sm md:text-[15px]">Маршрут →</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex-[1.4_1_480px] min-w-0 rounded-[20px] md:rounded-3xl overflow-hidden border border-line bg-surface">
                <div id="exchange-map" class="h-[320px] md:h-full md:min-h-[420px]"></div>
            </div>
        </div>
    </section>
    <script>
        (function () {
            var points = @json($exchangePointsMap);

            var map = L.map('exchange-map', { scrollWheelZoom: false });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19,
            }).addTo(map);

            var bounds = [];

            points.forEach(function (point, i) {
                var latlng = [point.coords[0], point.coords[1]];
                bounds.push(latlng);

                L.marker(latlng, {
                    icon: L.divIcon({
                        className: '',
                        html: '<div class="map-pin"><span>' + (i + 1) + '</span></div>',
                        iconSize: [40, 40],
                        iconAnchor: [6, 40],
                        popupAnchor: [14, -40],
                    }),
                })
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

    {{-- Callback CTA --}}
    <section id="callback" class="hidden md:block max-w-[1200px] mx-auto px-4 md:px-6 pb-12 md:pb-24 scroll-mt-20">
        <div class="bg-lime text-ink rounded-3xl md:rounded-[32px] p-6 md:p-14 flex flex-wrap gap-6 md:gap-10 items-center justify-between">
            <div class="flex-[1_1_420px] min-w-0">
                <h2 class="font-display font-extrabold text-[28px] md:text-[40px] leading-[1.08] tracking-[-0.03em] mb-3">Замовте дзвінок</h2>
                <p class="text-base md:text-lg font-semibold text-[#1E2A14]">Залиште ім’я і телефон — ми передзвонимо найближчим часом.</p>
            </div>
            <form class="js-contact-form flex-[1_1_460px] min-w-0 flex flex-wrap gap-3">
                @csrf
                <label class="flex-[1_1_180px] flex flex-col gap-1.5 text-[13px] font-extrabold">
                    Ім’я
                    <input type="text" name="contact_name" placeholder="Ваше ім’я" required
                           class="min-h-14 rounded-2xl px-[18px] text-base font-medium bg-white text-ink placeholder-[#6B7385] focus:outline-none focus:ring-2 focus:ring-ink">
                </label>
                <label class="flex-[1_1_180px] flex flex-col gap-1.5 text-[13px] font-extrabold">
                    Телефон
                    <input type="tel" name="contact_phone" placeholder="+380 XX XXX XX XX" required
                           class="min-h-14 rounded-2xl px-[18px] text-base font-medium bg-white text-ink placeholder-[#6B7385] focus:outline-none focus:ring-2 focus:ring-ink">
                </label>
                <button type="submit" class="self-end w-full sm:w-auto min-h-14 px-7 rounded-2xl bg-ink text-white font-extrabold hover:bg-[#1F2536] active:scale-[0.98] transition-all">
                    Відправити
                </button>
            </form>
        </div>
    </section>
@endsection
