<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConversionRate;
use App\Models\Currency;
use Illuminate\Http\Request;

class ConversionRateController extends Controller
{
    public function index()
    {
        $conversionRates = ConversionRate::with(['fromCurrency', 'toCurrency'])->get();
        dd($conversionRates);
        return view('admin.conversion-rates.index', compact('conversionRates'));
    }

    public function create()
    {
        $currencies = Currency::all();
        return view('admin.conversion-rates.create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'currency_from' => 'required|exists:currencies,currency_code',
            'currency_to' => 'required|exists:currencies,currency_code',
            'conversion_rate' => 'required|numeric|min:0',
        ]);

        ConversionRate::create($validated);

        return redirect()->route('admin.conversion-rates.index')
            ->with('success', 'Курс конвертації успішно створено');
    }

    public function edit(ConversionRate $conversionRate)
    {
        $currencies = Currency::all();
        return view('admin.conversion-rates.edit', compact('conversionRate', 'currencies'));
    }

    public function update(Request $request, ConversionRate $conversionRate)
    {
        $validated = $request->validate([
            'currency_from' => 'required|exists:currencies,currency_code',
            'currency_to' => 'required|exists:currencies,currency_code',
            'conversion_rate' => 'required|numeric|min:0',
        ]);

        $conversionRate->update($validated);

        return redirect()->route('admin.conversion-rates.index')
            ->with('success', 'Курс конвертації успішно оновлено');
    }

    public function destroy(ConversionRate $conversionRate)
    {
        $conversionRate->delete();

        return redirect()->route('admin.conversion-rates.index')
            ->with('success', 'Курс конвертації успішно видалено');
    }
} 