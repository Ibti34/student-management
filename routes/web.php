<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\StudentController;



Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
Route::get('/about', function () {
    return Inertia::render('About');
});



Route::post('/student', function () {

    dd(request()->all());
});

Route::get('/contact', function () {
    return Inertia::render('Contact');
});


Route::get('/', function () {
    return Inertia::render('Home');
});


Route::get('/students', [StudentController::class, 'index'])
    ->name('students.Index');

Route::get('/students/create', [StudentController::class, 'create']);

Route::post('/students', [StudentController::class, 'store']);
