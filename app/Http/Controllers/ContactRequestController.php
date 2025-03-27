<?php

namespace App\Http\Controllers;

use App\Models\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactRequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'contact_name' => 'required|string|max:255',
                'contact_phone' => 'required|string|max:255',
            ]);

            ContactRequest::create([
                'contact_name' => $validated['contact_name'],
                'contact_phone' => $validated['contact_phone'],
                'request_date' => now(),
                'is_pending' => true,
            ]);

            return response()->json(['message' => 'Запит успішно створено']);
        } catch (\Exception $e) {
            Log::error('Помилка при створенні запиту: ' . $e->getMessage());
            return response()->json(['error' => 'Помилка при створенні запиту'], 500);
        }
    }
}
