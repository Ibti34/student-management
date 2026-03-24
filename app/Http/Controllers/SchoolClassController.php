<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SchoolClassController extends Controller
{
    private function ensureStaff(Request $request): void
    {
        abort_unless($request->user()?->isAdmin() || $request->user()?->isTeacher(), 403);
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->isAdmin(), 403);
    }

    public function index(Request $request)
    {
        $this->ensureStaff($request);

        $classes = SchoolClass::query()
            ->withCount('students')
            ->orderBy('academic_year', 'desc')
            ->orderBy('name')
            ->orderBy('section')
            ->get();

        return Inertia::render('Classes/Index', [
            'classes' => $classes,
        ]);
    }

    public function create(Request $request)
    {
        $this->ensureAdmin($request);

        return Inertia::render('Classes/Create');
    }

    public function store(Request $request)
    {
        $this->ensureAdmin($request);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
            'homeroom_teacher' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1|max:200',
        ]);

        SchoolClass::create($data);

        return redirect()->route('classes.index');
    }

    public function edit(Request $request, SchoolClass $class)
    {
        $this->ensureAdmin($request);

        return Inertia::render('Classes/Edit', [
            'schoolClass' => $class,
        ]);
    }

    public function update(Request $request, SchoolClass $class)
    {
        $this->ensureAdmin($request);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
            'homeroom_teacher' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1|max:200',
        ]);

        $class->update($data);

        return redirect()->route('classes.index');
    }

    public function destroy(Request $request, SchoolClass $class)
    {
        $this->ensureAdmin($request);

        $class->delete();

        return redirect()->route('classes.index');
    }
}
