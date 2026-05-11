<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
use App\Models\ConversionRate;
use App\Models\ExchangePoint;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $rates = CurrencyRate::with('currency')
            ->whereHas('currency', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('position', 'asc')
            ->get();

        $conversionRates = ConversionRate::with(['currencyFrom', 'currencyTo'])
            ->whereHas('currencyFrom', function ($query) {
                $query->where('is_active', true);
            })
            ->whereHas('currencyTo', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('position', 'asc')
            ->get();

        $exchangePoints = ExchangePoint::where('is_active', true)->get();
        $socialLinks = SocialLink::first();

        return view('home', compact('rates', 'conversionRates', 'exchangePoints', 'socialLinks'));
    }
}
