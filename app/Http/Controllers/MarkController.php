<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarkController extends Controller
{
    /**
     * Display the marks management page.
     */
    public function index()
    {
        return Inertia::render('Marks/Index', [
            // We fetch Users with the 'student' role because your migration 
            // links student_id to the users table.
            'students' => User::where('role', 'student')->get(['id', 'name']),

            'subjects' => Subject::all(['id', 'name']),

            // Load marks with their related student (User) and subject
            'marks'    => Mark::with(['student', 'subject'])->latest()->get(),
        ]);
    }

    /**
     * Save or update a student's mark.
     */
    public function store(Request $request)
    {
        // Security check
        if (auth()->user()->role !== 'teacher') {
            abort(403, 'Only teachers can enter marks.');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'score'      => 'required|integer|min:0|max:100',
            'term'       => 'nullable|string',
        ]);

        // Use updateOrCreate to prevent duplicate entries for the same student/subject
        Mark::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'subject_id' => $validated['subject_id'],
                'term'       => $request->term ?? 'Term 1',
            ],
            [
                'score'      => $validated['score'],
                'teacher_id' => auth()->id(), // Automatically grab the logged-in teacher's ID
            ]
        );

        return redirect()->back()->with('message', 'Mark recorded successfully!');
    }

    /**
     * Remove a mark from the database.
     */
    public function destroy($id)
    {
        // Security: Only teachers can delete marks
        if (auth()->user()->role !== 'teacher') {
            abort(403, 'Unauthorized action.');
        }

        $mark = Mark::findOrFail($id);
        $mark->delete();

        return redirect()->back()->with('message', 'Mark deleted successfully.');
    }
    public function update(Request $request, $id)
    {
        $mark = Mark::findOrFail($id);

        $mark->update([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'score' => $request->score,
            'term' => $request->term
        ]);

        return redirect()->back()->with('message', 'Mark updated');
    }
}
