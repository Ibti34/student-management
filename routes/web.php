<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StudentController;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        $attendanceData = \App\Models\Attendance::selectRaw('DATE(date) as day, COUNT(*) as count')
            ->where('status', 'present')
            ->where('date', '>=', now()->subDays(7))
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get();

        $attendanceTrend = collect(range(6, 0))
            ->map(function ($daysAgo) use ($attendanceData) {
                $day = now()->subDays($daysAgo)->toDateString();
                $record = $attendanceData->firstWhere('day', $day);

                return [
                    'label' => now()->subDays($daysAgo)->format('M j'),
                    'count' => (int) ($record->count ?? 0),
                ];
            });

        return Inertia::render('Dashboard', [
            'studentsCount' => User::where('role', 'student')->count(),
            'usersCount' => $user->isStudent() ? 0 : User::count(),
            'registeredUsers' => $user->isStudent() ? [] : User::latest()->get(),
            'attendanceChartData' => [
                'labels' => $attendanceTrend->pluck('label'),
                'datasets' => $attendanceTrend->pluck('count'),
            ],
        ]);
    })->name('dashboard');

    Route::resource('students', StudentController::class);
    Route::resource('classes', SchoolClassController::class)->except('show');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/bulk', [AttendanceController::class, 'storeBulk'])->name('attendance.storeBulk');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
    Route::get('/my-attendance', [AttendanceController::class, 'myAttendance'])->name('attendance.my');
    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');

    Route::get('/profile', [ProfileController::class, 'edit']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    Route::get('/my-marks', [MarkController::class, 'myMarks'])->name('marks.my');
    Route::get('/marks', [MarkController::class, 'index'])->name('marks.index');
    Route::post('/marks', [MarkController::class, 'store'])->name('marks.store');
    Route::put('/marks/{mark}', [MarkController::class, 'update'])->name('marks.update');
    Route::delete('/marks/{mark}', [MarkController::class, 'destroy'])->name('marks.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
