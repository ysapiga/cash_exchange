@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Створення курсу конвертації</h1>
            <a href="{{ route('admin.conversion-rates.index') }}" class="text-gray-600 hover:text-gray-900">
                Назад до списку
            </a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.conversion-rates.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="currency_from">
                    З валюти
                </label>
                <select name="currency_from" id="currency_from" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Оберіть валюту</option>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->currency_code }}">{{ $currency->icon }} {{ $currency->currency_code }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="currency_to">
                    В валюту
                </label>
                <select name="currency_to" id="currency_to" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Оберіть валюту</option>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->currency_code }}">{{ $currency->icon }} {{ $currency->currency_code }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="conversion_rate">
                    Курс конвертації
                </label>
                <input type="number" step="0.0001" name="conversion_rate" id="conversion_rate" value="{{ old('conversion_rate') }}" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Створити
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 