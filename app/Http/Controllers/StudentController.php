<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user(), 403);

        $user = $request->user();

        $students = Student::query()
            ->with('schoolClass')
            ->when($user->isStudent(), function ($query) use ($user) {
                $query->where(function ($studentQuery) use ($user) {
                    $studentQuery->where('user_id', $user->id)
                        ->orWhere(function ($fallback) use ($user) {
                            $fallback->whereNull('user_id')
                                ->where('email', $user->email)
                                ->where('name', $user->name);
                        });
                });
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($studentQuery) use ($search) {
                    $studentQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(!$user->isStudent() && $request->filled('class_id'), function ($query) use ($request) {
                $query->where('school_class_id', $request->integer('class_id'));
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Students/Index', [
            'students' => $students,
            'filters' => $request->only('search', 'class_id'),
            'isStudentView' => $user->isStudent(),
            'classes' => $user->isStudent()
                ? []
                : SchoolClass::orderBy('academic_year', 'desc')
                    ->orderBy('name')
                    ->orderBy('section')
                    ->get(['id', 'name', 'section', 'academic_year']),
        ]);
    }

    public function create(Request $request)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        return Inertia::render('Students/Create', [
            'classes' => SchoolClass::orderBy('academic_year', 'desc')
                ->orderBy('name')
                ->orderBy('section')
                ->get(['id', 'name', 'section', 'academic_year']),
        ]);
    }

    public function store(Request $request)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'age' => 'required|integer|min:1',
            'phone' => 'required|string|max:20',
            'university' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'year_of_study' => 'nullable|integer|min:1|max:8',
            'role' => 'required|string|in:student,teacher,admin',
            'school_class_id' => 'nullable|exists:school_classes,id',
        ]);

        $data['user_id'] = User::where('email', $data['email'])->value('id');

        Student::create($data);

        return redirect()->route('students.index');
    }

    public function edit(Request $request, Student $student)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        return Inertia::render('Students/Edit', [
            'student' => $student,
            'classes' => SchoolClass::orderBy('academic_year', 'desc')
                ->orderBy('name')
                ->orderBy('section')
                ->get(['id', 'name', 'section', 'academic_year']),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('students', 'email')->ignore($student->id)],
            'age' => 'required|integer|min:1',
            'phone' => 'required|string|max:20',
            'university' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'year_of_study' => 'nullable|integer|min:1|max:8',
            'role' => 'required|string|in:student,teacher,admin',
            'school_class_id' => 'nullable|exists:school_classes,id',
        ]);

        $data['user_id'] = User::where('email', $data['email'])->value('id');

        $student->update($data);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Request $request, Student $student)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $student->delete();

        return redirect()->route('students.index');
    }
}
