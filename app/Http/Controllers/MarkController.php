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
        return Inertia::render('Marks/Index', [
            // Filters only users with the 'student' role
            'students' => User::where('role', 'student')->select('id', 'name')->get(),
            'subjects' => Subject::select('id', 'name')->get(),
            'marks'    => Mark::with(['student', 'subject'])->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'score'      => 'required|integer|min:0|max:100',
            'term'       => 'nullable|string',
        ]);

        Mark::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'subject_id' => $validated['subject_id'],
                'term'       => $request->term ?? 'Term 1',
            ],
            [
                'score'      => $validated['score'],
                'teacher_id' => auth()->id(),
            ]
        );

        return redirect()->back()->with('message', 'Mark saved!');
    }

    public function update(Request $request, Mark $mark)
    {
        $mark->update($request->all());
        return redirect()->back()->with('message', 'Mark updated');
    }

    public function destroy(Mark $mark)
    {
        $mark->delete();
        return redirect()->back()->with('message', 'Mark deleted');
    }
}
