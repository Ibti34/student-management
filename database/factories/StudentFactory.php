<?php

namespace Database\Factories;

use App\Models\SchoolClass;
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
            'year_of_study' => fake()->numberBetween(1, 5),
            'role' => 'student',
            'user_id' => \App\Models\User::factory(), // This links a user automatically
            'school_class_id' => SchoolClass::factory(),
        ];
    }
}
