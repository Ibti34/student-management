<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // Create subjects
        \App\Models\Subject::updateOrCreate(
            ['code' => 'SE201'],
            ['name' => 'Web Development', 'credit_hours' => 3]
        );
        \App\Models\Subject::updateOrCreate(
            ['code' => 'NET301'],
            ['name' => 'Networking', 'credit_hours' => 4]
        );
        \App\Models\Subject::updateOrCreate(
            ['code' => 'UIX302'],
            ['name' => 'User Interface Design', 'credit_hours' => 3]
        );

        $classes = collect([
            ['name' => 'Software Engineering', 'section' => 'Year 1', 'academic_year' => '2026/2027', 'capacity' => 40],
            ['name' => 'Computer Science', 'section' => 'Year 2', 'academic_year' => '2026/2027', 'capacity' => 40],
            ['name' => 'Information Systems', 'section' => 'Year 3', 'academic_year' => '2026/2027', 'capacity' => 35],
            ['name' => 'Networking', 'section' => 'Year 4', 'academic_year' => '2026/2027', 'capacity' => 30],
        ])->map(fn ($classroom) => SchoolClass::updateOrCreate(
            [
                'name' => $classroom['name'],
                'section' => $classroom['section'],
                'academic_year' => $classroom['academic_year'],
            ],
            [
                'homeroom_teacher' => 'TBD',
                'capacity' => $classroom['capacity'],
            ]
        ));

        Student::factory(10)
            ->sequence(fn ($sequence) => [
                'school_class_id' => $classes[$sequence->index % $classes->count()]->id,
            ])
            ->create();
    }
}
