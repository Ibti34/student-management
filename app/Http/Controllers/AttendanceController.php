<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index()
    {
        $students = Student::all();

        $attendances = Attendance::with('student')->get();

        return Inertia::render('Attendance/Index', [
            'students' => $students,
            'attendances' => $attendances
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'date' => 'required',
            'status' => 'required'
        ]);

        \App\Models\Attendance::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'date' => $request->date
            ],
            [
                'status' => $request->status
            ]
        );

        return redirect()->back()->with('success', 'Attendance saved successfully!');
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->back()->with('success', 'Attendance deleted!');
    }
}
