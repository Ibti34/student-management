<?php

use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('students can view their transcript summary on the marks page', function () {
    $studentUser = User::factory()->create([
        'role' => 'student',
        'name' => 'Meklit',
        'email' => 'meklit@example.com',
    ]);

    Student::factory()->create([
        'user_id' => $studentUser->id,
        'name' => 'Meklit',
        'email' => 'meklit@example.com',
        'department' => 'UI/UX Design',
    ]);

    $teacher = User::factory()->create(['role' => 'teacher']);

    $subject = Subject::create([
        'name' => 'User Interface Design',
        'code' => 'UIX302',
        'credit_hours' => 3,
    ]);

    Mark::create([
        'student_id' => $studentUser->id,
        'teacher_id' => $teacher->id,
        'subject_id' => $subject->id,
        'score' => 88,
        'semester' => 'Semester 1',
        'academic_year' => '2026/2027',
        'term' => 'Semester 1',
    ]);

    $this->actingAs($studentUser)
        ->get(route('marks.my'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Marks/Index')
            ->where('user_role', 'student')
            ->where('transcriptSummary.gpa', 4)
            ->where('transcriptSummary.passed_courses', 1)
            ->where('marks.0.letter_grade', 'A')
            ->where('marks.0.subject.code', 'UIX302')
        );
});
