<?php

namespace App\Observers;

use App\Models\Customer;
use Illuminate\Support\Facades\Http;

class CustomerObserver
{
    private array $map = [
        'name' => 'Імʼя',
        'last_name' => 'Прізвище',
        'telephone' => 'Телефон',
        'email' => 'Email',
        'date_of_birth' => 'Дата народження',
        'note' => 'Примітка',
    ];

    public function created(Customer $customer): void
    {
        $message = "👤 Новий клієнт створений\n\n";
        $message .= "Номер Карти: {$customer->identifier}\n";

        // Використовуємо мапу для полів
        foreach ($this->map as $field => $label) {
            $value = $customer->$field ?? '';
            $message .= "{$label}: {$value}\n";
        }

        Http::post(
            'https://api.telegram.org/bot' . config('services.telegram.bot_token') . '/sendMessage',
            [
                'chat_id' => config('services.telegram.chat_id'),
                'text' => $message,
            ]
        );
    }

    public function updated(Customer $customer): void
    {
        $changes = $customer->getChanges();

        if (empty($changes)) {
            return;
        }

        $message = "✏️ Клієнт оновлений\n\n";
        $message .= "Номер Карти: {$customer->identifier}\n";

        foreach ($changes as $field => $value) {
            if ($field === 'updated_at') {
                continue;
            }

            $field = $this->map[$field] ?? $field;

            $message .= "{$field}: {$value}\n";
        }

        Http::post(
            'https://api.telegram.org/bot' . config('services.telegram.bot_token') . '/sendMessage',
            [
                'chat_id' => config('services.telegram.chat_id'),
                'text' => $message,
            ]
        );

    }

    public function deleted(Customer $customer): void
    {
        $message = "🗑️ Клієнт видалений\n\n";
        $message .= "Номер Карти: {$customer->identifier}\n";

        Http::post(
            'https://api.telegram.org/bot' . config('services.telegram.bot_token') . '/sendMessage',
            [
                'chat_id' => config('services.telegram.chat_id'),
                'text' => $message,
            ]
        );
    }
}
