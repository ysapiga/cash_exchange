

@extends('layouts.app')

 @section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-[#262b3b] rounded-2xl shadow-xl p-4 md:p-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Курс валют</h1>
                    <p class="text-gray-400">Актуальний курс обміну валют на сьогодні</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="text-left py-4 px-2 md:px-4 text-gray-400 font-medium">Валюта</th>
                            <th class="text-right py-4 px-2 md:px-4 text-gray-400 font-medium">Купити</th>
                            <th class="text-right py-4 px-2 md:px-4 text-gray-400 font-medium">Продати</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rates as $rate)
                        <tr class="border-b border-gray-700 hover:bg-gray-700/50 transition-colors duration-200">
                            <td class="py-4 px-2 md:px-4">
                                <div class="flex items-center">
                                    <span class="text-lg font-semibold text-white">{{ $rate->currency->icon }} {{ $rate->currency->currency_code }}</span>

                                </div>
                            </td>
                            <td class="py-4 px-2 md:px-4 text-right">
                                <span class="text-lg font-semibold text-emerald-400">{{ rtrim(rtrim(number_format($rate->price_to_buy, 10, '.', ''), '0'), '.') }}</span>
                            </td>
                            <td class="py-4 px-2 md:px-4 text-right">
                                <span class="text-lg font-semibold text-red-400">{{ rtrim(rtrim(number_format($rate->price_to_sell, 10, '.', ''), '0'), '.') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 bg-[#262b3b] rounded-2xl shadow-xl p-4 md:p-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Курс конвертації</h1>
                    <p class="text-gray-400">Курс конвертації між валютами</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="text-center py-4 px-2 md:px-4 text-gray-400 font-medium">Конвертація</th>
                            <th class="text-center py-4 px-2 md:px-4 text-gray-400 font-medium">Курс</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($conversionRates as $rate)
                        <tr class="border-b border-gray-700 hover:bg-gray-700/50 transition-colors duration-200">
                            <td class="py-4 px-2 md:px-4">
                                <div class="flex items-center justify-center">
                                    <div class="flex items-center">
                                        <span class="text-lg font-semibold text-white">{{ $rate->currencyFrom->icon }} {{ $rate->currencyFrom->currency_code }}</span>
                                        <svg class="w-6 h-6 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                        <span class="text-lg font-semibold text-white">{{ $rate->currencyTo->icon }} {{ $rate->currencyTo->currency_code }}</span>
                                    </div>

                                </div>
                            </td>
                            <td class="py-4 px-2 md:px-4 text-center">
                                <span class="text-lg font-semibold text-emerald-400">{{ rtrim(rtrim(number_format($rate->conversion_rate, 10, '.', ''), '0'), '.') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($exchangePoints->isNotEmpty())
        <div class="mt-8 bg-[#262b3b] rounded-2xl shadow-xl overflow-hidden" style="isolation: isolate;">
            <div id="exchange-map" style="height: 450px;"></div>
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

