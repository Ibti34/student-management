<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{

    // Show attendance page
    public function index(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        $students = Student::all();

        $attendanceRecords = Attendance::where('date', $date)
            ->get()
            ->keyBy('student_id');

        return Inertia::render('Attendance/Index', [
            'students' => $students,
            'attendanceRecords' => $attendanceRecords,
            'selectedDate' => $date
        ]);
    }


    // Save attendance
    public function storeBulk(Request $request)
    {
        $date = $request->date;
        $records = $request->attendance;

        foreach ($records as $studentId => $status) {

            if (!$status) {
                continue;
            }

            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $date
                ],
                [
                    'status' => $status
                ]
            );
        }

        return redirect()->route('attendance.index', ['date' => $date])
            ->with('success', 'Attendance saved successfully!');
    }


    // Attendance history (Admin / Teacher)
    public function history()
    {
        $attendance = \App\Models\Attendance::with('student')
            ->orderBy('date', 'desc')
            ->get();

        return Inertia::render('Attendance/History', [
            'attendance' => $attendance
        ]);
    }

    // Student personal attendance
    public function myAttendance()
    {
        $student = Student::first(); // simple version for now

        $attendance = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        return Inertia::render('Attendance/MyAttendance', [
            'attendance' => $attendance
        ]);
    }


    // Delete attendance
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->back()
            ->with('success', 'Attendance deleted successfully');
    }
}
