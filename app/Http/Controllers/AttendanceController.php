<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    private function ensureStaff(Request $request): void
    {
        abort_unless($request->user()?->isAdmin() || $request->user()?->isTeacher(), 403);
    }

    /**
     * Show attendance page (Marking Attendance)
     */
    public function index(Request $request)
    {
        $this->ensureStaff($request);

        $date = $request->date ?? now()->toDateString();
        $students = Student::query()
            ->with('schoolClass')
            ->when($request->filled('class_id'), function ($query) use ($request) {
                $query->where('school_class_id', $request->integer('class_id'));
            })
            ->get();

        $attendanceRecords = Attendance::where('date', $date)
            ->get()
            ->keyBy('student_id');

        return Inertia::render('Attendance/Index', [
            'students' => $students,
            'attendanceRecords' => $attendanceRecords,
            'selectedDate' => $date,
            'classes' => SchoolClass::orderBy('academic_year', 'desc')
                ->orderBy('name')
                ->orderBy('section')
                ->get(['id', 'name', 'section', 'academic_year']),
            'selectedClassId' => $request->input('class_id'),
        ]);
    }

    /**
     * Save/Update attendance in bulk
     */
    public function storeBulk(Request $request)
    {
        $this->ensureStaff($request);

        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'nullable|in:present,absent,late',
        ]);

        foreach ($request->attendance as $studentId => $status) {
            if (!$status) {
                continue;
            }

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
        $this->ensureStaff($request);

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
        abort_unless($user?->isStudent(), 403);

        $student = Student::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($fallback) use ($user) {
                        $fallback->whereNull('user_id')
                            ->where('email', $user->email)
                            ->where('name', $user->name);
                    });
            })
            ->first();

        if (!$student) {
            return Inertia::render('Attendance/MyAttendance', [
                'attendance' => (object)[],
                'daysInMonth' => now()->daysInMonth,
                'studentName' => $user->name
            ]);
        }

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Fetch and format so keys are pure integers (1, 2, 3...)
        $attendanceRecords = $student->attendances()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->mapWithKeys(function ($item) {
                $day = (int) \Carbon\Carbon::parse($item->date)->format('j'); // 'j' = day without leading zeros
                return [$day => $item];
            })
            ->toArray(); // Convert to array to ensure clean JSON object

        return Inertia::render('Attendance/MyAttendance', [
            'attendance' => (object)$attendanceRecords, // Cast to object for JS key access
            'daysInMonth' => \Carbon\Carbon::createFromDate($year, $month)->daysInMonth,
            'currentMonth' => \Carbon\Carbon::createFromDate($year, $month)->format('F'),
            'currentYear' => $year,
            'studentName' => $student->name
        ]);
    }
    /**
     * Delete a specific attendance record
     */
    public function destroy(Request $request, Attendance $attendance)
    {
        $this->ensureStaff($request);

        $attendance->delete();
        return redirect()->back()->with('success', 'Attendance deleted successfully');
    }
}
