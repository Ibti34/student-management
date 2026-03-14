<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'age' => fake()->numberBetween(18, 25),
            'phone' => fake()->phoneNumber(),
            'university' => 'Addis Ababa University', // Example value
            'department' => 'Software Engineering',   // Example value
            'role' => 'student',
            'user_id' => \App\Models\User::factory(), // This links a user automatically
        ];
    }
}
