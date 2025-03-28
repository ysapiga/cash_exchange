<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
use App\Models\ConversionRate;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $rates = CurrencyRate::with('currency')
            ->whereHas('currency', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $conversionRates = ConversionRate::with(['currencyFrom', 'currencyTo'])
            ->whereHas('currencyFrom', function ($query) {
                $query->where('is_active', true);
            })
            ->whereHas('currencyTo', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home', compact('rates', 'conversionRates'));
    }
}
