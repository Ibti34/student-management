<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Student;


class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();

        return Inertia::render('Students/Index', [
            'students' => $students,
        ]);
    }
    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'age' => 'required|integer',
            'university' => 'required',
        ]);

        Student::create($data);

        return redirect('/students');
    }

    public function create()
    {
        return Inertia::render('Students/Create');
    }


    public function edit(Student $student)
    {
        return Inertia::render('Students/Edit', [
            'student' => $student
        ]);
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.Index');
    }
}
