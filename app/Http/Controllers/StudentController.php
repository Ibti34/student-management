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
}
