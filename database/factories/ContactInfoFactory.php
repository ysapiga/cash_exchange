<?php

namespace Database\Factories;

use App\Models\ContactInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactInfo>
 */
class ContactInfoFactory extends Factory
{
    protected $model = ContactInfo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address' => $this->faker->address(),
            'coordinates' => $this->faker->latitude() . ',' . $this->faker->longitude(),
            'telegram' => '@' . $this->faker->userName(),
            'instagram' => '@' . $this->faker->userName(),
            'telephone' => $this->faker->phoneNumber(),
        ];
    }
}
