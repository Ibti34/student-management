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

    public function storeBulk(Request $request)
    {
        $date = $request->date;
        $records = $request->attendance; // array: [student_id => status]

        foreach ($records as $studentId => $status) {
            \App\Models\Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $date
                ],
                [
                    'status' => $status
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance saved successfully!');
    }
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->back()->with('success', 'Attendance deleted!');
    }
}
