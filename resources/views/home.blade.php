@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-[#282741] rounded-2xl shadow-xl p-6 md:p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Курси валют</h1>
                <p class="text-gray-400">Актуальні курси обміну валют на сьогодні</p>
            </div>
            <div class="mt-4 md:mt-0">
                <p class="text-sm text-gray-400">Оновлено: {{ now()->format('d.m.Y H:i') }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-4 px-4 text-gray-400 font-medium">Валюта</th>
                        <th class="text-right py-4 px-4 text-gray-400 font-medium">Купити</th>
                        <th class="text-right py-4 px-4 text-gray-400 font-medium">Продати</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rates as $rate)
                    <tr class="border-b border-gray-700 hover:bg-gray-700/50 transition-colors duration-200">
                        <td class="py-4 px-4">
                            <div class="flex items-center">
                                <span class="text-lg font-semibold text-white">{{ $rate->currency->icon }} {{ $rate->currency->currency_code }}</span>
                                <span class="ml-2 text-sm text-gray-400">Оновлено: {{ $rate->updated_at->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <span class="text-lg font-semibold text-emerald-400">{{ number_format($rate->price_to_buy, 2) }}</span>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <span class="text-lg font-semibold text-red-400">{{ number_format($rate->price_to_sell, 2) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 bg-[#282741] rounded-2xl shadow-xl overflow-hidden">
        <iframe 
            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=вул.+Автомобілістів,+18,+Мукачево,+Закарпатська+область,+Україна" 
            width="100%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
        </iframe>
    </div>
</div>
@endsection
