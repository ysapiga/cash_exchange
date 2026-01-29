<?php

namespace App\Observers;

use App\Models\ContactRequest;
use Illuminate\Support\Facades\Http;

class ContactRequestObserver
{
    public function created(ContactRequest $request): void
    {
        $message = "📩 Надійшов запит на звінок\n\n";
        $message .= "Імʼя: {$request->contact_name}\n";
        $message .= "Телефон: {$request->contact_phone}\n";
        $message .= "Дата запиту: " . $request->request_date->format('Y-m-d H:i') . "\n";

        Http::post(
            'https://api.telegram.org/bot' . config('services.telegram.bot_token') . '/sendMessage',
            [
                'chat_id' => config('services.telegram.chat_id'),
                'text' => $message,
            ]
        );
    }
}
