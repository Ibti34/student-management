<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolClassFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Software Engineering', 'Computer Science', 'Information Systems']),
            'section' => fake()->randomElement(['Year 1', 'Year 2', 'Year 3', 'Year 4']),
            'academic_year' => '2026/2027',
            'homeroom_teacher' => fake()->name(),
            'capacity' => fake()->numberBetween(25, 50),
        ];
    }
}
