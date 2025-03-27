<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactRequest>
 */
class ContactRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contact_name' => fake()->name(),
            'contact_phone' => fake()->phoneNumber(),
            'request_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'is_pending' => fake()->boolean(80), // 80% шанс що запит буде в очікуванні
        ];
    }
}
