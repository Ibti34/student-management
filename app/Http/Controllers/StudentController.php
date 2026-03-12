<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"); // FIXED: Changed => to ->
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();
        return Inertia::render('Students/Index', [
            'students' => $students,
            'filters' => $request->only('search'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Students/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'age' => 'required|integer|min:1',
            'phone' => 'required|numeric',
            'university' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'role' => 'required|string|in:student,teacher,admin',
        ]);

        Student::create($data);

        return redirect()->route('students.index');
    }

    public function edit(Student $student)
    {
        return Inertia::render('Students/Edit', [
            'student' => $student
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'age' => 'required|integer|min:1',
            'phone' => 'required|numeric',
            'university' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'role' => 'required|string|in:student,teacher,admin',
        ]);

        $student->update($data);

        return redirect()->route('students.index');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index');
    }
}
