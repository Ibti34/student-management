<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
Route::get('/about', function () {
    return Inertia::render('About', [
        'name' => 'Ibtisam kedir',
        'email' => 'ibtisamkedir076@example.com',
        'age' => 22,
        'university' => 'Arbaminch University',
    ]);
});

Route::get('/student', function () {
    return Inertia::render('Student');
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
