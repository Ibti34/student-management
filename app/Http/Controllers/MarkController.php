<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarkController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Teachers see all, Students see only theirs
        $marks = Mark::with(['student', 'subject'])
            ->when($user->role === 'student', function ($query) use ($user) {
                return $query->where('student_id', $user->id);
            })
            ->latest()
            ->get();

        $students = $user->role === 'teacher'
            ? User::where('role', 'student')->select('id', 'name')->get()
            : [];

        return Inertia::render('Marks/Index', [
            'marks' => $marks,
            'students' => $students,
            'subjects' => Subject::all(),
            'user_role' => $user->role,
        ]);
    }

    // THIS IS THE METHOD THAT WAS MISSING
    public function myMarks()
    {
        $user = auth()->user();

        $marks = Mark::with(['student', 'subject'])
            ->where('student_id', $user->id)
            ->latest()
            ->get();

        return Inertia::render('Marks/Index', [
            'marks' => $marks,
            'students' => [],
            'subjects' => Subject::all(),
            'user_role' => $user->role,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'required|integer|min:0|max:100',
            'term' => 'nullable|string',
        ]);

        Mark::create(array_merge($validated, [
            'teacher_id' => auth()->id(),
            'term' => $validated['term'] ?? 'Term 1'
        ]));

        return redirect()->back();
    }

    public function update(Request $request, Mark $mark)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'required|integer|min:0|max:100',
            'term' => 'nullable|string',
        ]);

        $mark->update($validated);

        return redirect()->back();
    }

    public function destroy(Mark $mark)
    {
        $mark->delete();
        return redirect()->back();
    }
}
