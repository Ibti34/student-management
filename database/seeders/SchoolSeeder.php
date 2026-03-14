<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // Create subjects
        \App\Models\Subject::updateOrCreate(['name' => 'web development']);
        \App\Models\Subject::updateOrCreate(['name' => 'networking']);

        // Create 10 random students using the factory
        \App\Models\Student::factory(10)->create();
    }
}
