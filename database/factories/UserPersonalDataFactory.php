<?php

namespace Database\Factories;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPersonalData>
 */
class UserPersonalDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => \Illuminate\Support\Str::uuid(),
            'first_name' => substr(fake()->firstName(), 0, 25),
            'last_name' => substr(fake()->lastName(), 0, 25),
            'gender' => fake()->randomElement(Gender::toArray()),
            'birth_date' => now()->format('Y-m-d'),
            'job' => fake()->jobTitle(),
            'address' => fake()->address(),
        ];
    }
}
