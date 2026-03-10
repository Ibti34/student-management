<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Show attendance page (Marking Attendance)
     */
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

    /**
     * Save/Update attendance in bulk
     */
    public function storeBulk(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array'
        ]);

        foreach ($request->attendance as $studentId => $status) {
            if (!$status) continue;

            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'date' => $request->date],
                ['status' => $status]
            );
        }

        // Use 'with' to send the success message to Inertia
        return redirect()->back()->with('success', 'Attendance updated successfully!');
    }

    /**
     * Attendance history (Admin / Teacher View) - FIXED
     */
    public function history(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $students = Student::all();

        // Grouping by student_id is crucial for the Grid view
        $attendance = Attendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy('student_id');

        return Inertia::render('Attendance/History', [
            'students' => $students,
            'attendance' => $attendance,
            'daysInMonth' => Carbon::createFromDate($year, $month)->daysInMonth,
            'currentMonth' => Carbon::createFromDate($year, $month)->format('F'),
            'currentYear' => $year,
        ]);
    }

    /**
     * Student personal attendance (Monthly Grid View)
     */
    public function myAttendance(Request $request)
    {
        $user = Auth::user();

        // Safety check: Ensure user has a student profile
        if (!$user || !$user->student) {
            return Inertia::render('Attendance/MyAttendance', [
                'attendance' => (object)[],
                'daysInMonth' => now()->daysInMonth
            ]);
        }

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $dateContext = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $dateContext->daysInMonth;

        $attendanceRecords = Attendance::where('student_id', $user->student->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy(function ($item) {
                // Return integer day for easy lookup in Vue
                return (int) Carbon::parse($item->date)->format('d');
            });

        return Inertia::render('Attendance/MyAttendance', [
            'attendance' => $attendanceRecords,
            'daysInMonth' => $daysInMonth,
            'currentMonth' => $dateContext->format('F'),
            'currentYear' => $year,
            'studentName' => $user->name
        ]);
    }

    /**
     * Delete a specific attendance record
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->back()->with('success', 'Attendance deleted successfully');
    }
}
