

@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Currency Rates --}}
        <div class="bg-[#262b3b] rounded-2xl shadow-2xl overflow-hidden border border-gray-700/40">
            <div class="border-l-4 border-[#5F963B] px-5 md:px-8 py-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Курс валют</h1>
                    <p class="text-gray-400 text-sm mt-1">Актуальний курс обміну валют на сьогодні</p>
                </div>
            </div>

            <div>
                <table class="w-full">
                    <thead>
                        <tr class="border-y border-gray-700/60 bg-gray-800/30">
                            <th class="text-left py-3 px-4 md:px-8 text-gray-400 font-medium text-sm uppercase tracking-wider">Валюта</th>
                            <th class="text-right py-3 px-4 md:px-8">
                                <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase bg-emerald-500/10 text-emerald-400 px-2 md:px-3 py-1 rounded-full border border-emerald-500/20 whitespace-nowrap">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                    Купити
                                </span>
                            </th>
                            <th class="text-right py-3 px-4 md:px-8">
                                <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase bg-red-500/10 text-red-400 px-2 md:px-3 py-1 rounded-full border border-red-500/20 whitespace-nowrap">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
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

        {{-- Conversion Rates --}}
        <div class="bg-[#262b3b] rounded-2xl shadow-2xl overflow-hidden border border-gray-700/40">
            <div class="border-l-4 border-[#5F963B] px-5 md:px-8 py-5">
                <h2 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Курс конвертації</h2>
                <p class="text-gray-400 text-sm mt-1">Курс конвертації між валютами</p>
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
