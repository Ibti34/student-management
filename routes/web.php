
<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Student;
use App\Models\User;
use App\Http\Controllers\MarkController;



// Welcome page
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// About page
Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');


// | Protected Routes (Login Required)

Route::middleware(['auth'])->group(function () {


    // | Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            // Change this line to count Users with the 'student' role:
            'studentsCount' => User::where('role', 'student')->count(),

            'usersCount' => User::count(),
            'registeredUsers' => User::latest()->get(),
        ]);
    })->name('dashboard');



    // | Students

    Route::resource('students', StudentController::class);



    // | Attendance


    // Mark attendance page
    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    // Save attendance
    Route::post('/attendance/bulk', [AttendanceController::class, 'storeBulk'])
        ->name('attendance.storeBulk');

    // Attendance history (Admin / Teacher)
    Route::get('/attendance/history', [AttendanceController::class, 'history'])
        ->name('attendance.history');

    // Student personal attendance
    Route::get('/my-attendance', [AttendanceController::class, 'myAttendance'])
        ->name('attendance.my');

    // Delete attendance
    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])
        ->name('attendance.destroy');


    // profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});




// | Marks Management
Route::middleware(['auth'])->group(function () {
    // Specific student route
    Route::get('/my-marks', [MarkController::class, 'myMarks'])->name('marks.my');

    // General routes
    Route::get('/marks', [MarkController::class, 'index'])->name('marks.index');
    Route::post('/marks', [MarkController::class, 'store'])->name('marks.store');
    Route::put('/marks/{mark}', [MarkController::class, 'update'])->name('marks.update');
    Route::delete('/marks/{mark}', [MarkController::class, 'destroy'])->name('marks.destroy');
});
require __DIR__ . '/auth.php';
