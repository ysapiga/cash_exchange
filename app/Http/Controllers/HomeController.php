<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
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

        return view('home', compact('rates'));
    }
}
