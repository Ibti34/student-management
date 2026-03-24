<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;

class MarkController extends Controller
{
    private const SEMESTER_LABELS = [
        'Term 1' => 'Semester 1',
        'Term 2' => 'Semester 2',
    ];

    private function ensureStaff(Request $request): void
    {
        abort_unless($request->user()?->isAdmin() || $request->user()?->isTeacher(), 403);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        abort_unless($user?->isAdmin() || $user?->isTeacher() || $user?->isStudent(), 403);

        $students = ($user->isTeacher() || $user->isAdmin())
            ? User::with('student.schoolClass')->where('role', 'student')->select('id', 'name', 'email')->orderBy('name')->get()
            : [];

        return $this->renderMarksPage($request, $user, $students);
    }

    public function myMarks(Request $request)
    {
        $user = $request->user();
        abort_unless($user?->isStudent(), 403);

        return $this->renderMarksPage($request, $user, collect());
    }

    public function store(Request $request)
    {
        $this->ensureStaff($request);

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'required|integer|min:0|max:100',
            'semester' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
        ]);

        Mark::create(array_merge($validated, [
            'teacher_id' => auth()->id(),
            'term' => $validated['semester'],
        ]));

        return redirect()->back()->with('success', 'Result saved successfully.');
    }

    public function update(Request $request, Mark $mark)
    {
        $this->ensureStaff($request);

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'required|integer|min:0|max:100',
            'semester' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
        ]);

        $mark->update(array_merge($validated, [
            'term' => $validated['semester'],
        ]));

        return redirect()->back()->with('success', 'Result updated successfully.');
    }

    public function destroy(Request $request, Mark $mark)
    {
        $this->ensureStaff($request);

        $mark->delete();
        return redirect()->back()->with('success', 'Result deleted successfully.');
    }

    private function renderMarksPage(Request $request, User $user, Collection $students)
    {
        $selectedStudentId = $user->isStudent()
            ? $user->id
            : $request->integer('student_id');

        $marksQuery = Mark::with(['student.student.schoolClass', 'subject'])
            ->when($user->isStudent(), fn ($query) => $query->where('student_id', $user->id))
            ->when(!$user->isStudent() && $selectedStudentId, fn ($query) => $query->where('student_id', $selectedStudentId))
            ->when($request->filled('semester'), fn ($query) => $query->where('semester', $request->string('semester')->toString()))
            ->when($request->filled('academic_year'), fn ($query) => $query->where('academic_year', $request->string('academic_year')->toString()))
            ->latest();

        $marks = $marksQuery->get()->map(fn (Mark $mark) => $this->mapMark($mark));

        $subjectList = Subject::orderBy('name')
            ->get(['id', 'name', 'code', 'credit_hours']);

        $selectedStudent = $selectedStudentId
            ? User::with('student.schoolClass')->find($selectedStudentId)
            : null;

        [$semesterSummaries, $transcriptSummary] = $this->buildTranscriptSummary($marks, $selectedStudent);
        $studentResults = $this->buildStudentResults($marks);
        $allStudentMarks = $selectedStudentId
            ? Mark::with(['student.student.schoolClass', 'subject'])
                ->where('student_id', $selectedStudentId)
                ->latest()
                ->get()
                ->map(fn (Mark $mark) => $this->mapMark($mark))
            : collect();
        $reportData = $this->buildReportData($marks, $allStudentMarks, $selectedStudent);

        $availableAcademicYears = Mark::query()
            ->select('academic_year')
            ->whereNotNull('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year')
            ->values();

        $availableSemesters = Mark::query()
            ->select('semester')
            ->whereNotNull('semester')
            ->distinct()
            ->orderBy('semester')
            ->pluck('semester')
            ->map(fn ($semester) => $this->normalizeSemesterLabel($semester))
            ->unique()
            ->values();

        return Inertia::render('Marks/Index', [
            'marks' => $marks,
            'students' => $students,
            'subjects' => $subjectList,
            'user_role' => $user->role,
            'selectedStudent' => $selectedStudent ? [
                'id' => $selectedStudent->id,
                'name' => $selectedStudent->name,
                'email' => $selectedStudent->email,
                'department' => $selectedStudent->student?->department,
                'university' => $selectedStudent->student?->university,
                'program' => $selectedStudent->student?->schoolClass
                    ? trim($selectedStudent->student->schoolClass->name.' '.$selectedStudent->student->schoolClass->section)
                    : 'Unassigned',
                'advisor' => $selectedStudent->student?->schoolClass?->homeroom_teacher ?: 'Not assigned',
            ] : null,
            'semesterSummaries' => $semesterSummaries,
            'transcriptSummary' => $transcriptSummary,
            'studentResults' => $studentResults,
            'reportData' => $reportData,
            'filters' => [
                'student_id' => $selectedStudentId,
                'semester' => $request->string('semester')->toString(),
                'academic_year' => $request->string('academic_year')->toString(),
            ],
            'availableSemesters' => $availableSemesters,
            'availableAcademicYears' => $availableAcademicYears,
        ]);
    }

    private function buildTranscriptSummary($marks, ?User $selectedStudent): array
    {
        if ($marks->isEmpty()) {
            return [[], null];
        }

        $semesterSummaries = $marks
            ->groupBy(fn ($mark) => $mark['academic_year'].'|'.$mark['semester'])
            ->map(function ($group, $key) {
                [$academicYear, $semester] = explode('|', $key);
                $totalCredits = max((int) $group->sum(fn ($mark) => $mark['subject']['credit_hours'] ?? 0), 1);
                $weightedPoints = $group->sum('weighted_points');

                return [
                    'academic_year' => $academicYear,
                    'semester' => $semester,
                    'courses' => $group->count(),
                    'credits' => $group->sum(fn ($mark) => $mark['subject']['credit_hours'] ?? 0),
                    'average_score' => round($group->avg('score'), 2),
                    'gpa' => round($weightedPoints / $totalCredits, 2),
                    'passes' => $group->where('score', '>=', 40)->count(),
                ];
            })
            ->sortBy([
                ['academic_year', 'desc'],
                ['semester', 'asc'],
            ])
            ->values();

        $totalCredits = max((int) $marks->sum(fn ($mark) => $mark['subject']['credit_hours'] ?? 0), 1);
        $cumulativeWeightedPoints = $marks->sum('weighted_points');

        return [
            $semesterSummaries,
            [
                'student' => $selectedStudent ? [
                    'name' => $selectedStudent->name,
                    'email' => $selectedStudent->email,
                    'department' => $selectedStudent->student?->department,
                    'university' => $selectedStudent->student?->university,
                    'program' => $selectedStudent->student?->schoolClass
                        ? trim($selectedStudent->student->schoolClass->name.' '.$selectedStudent->student->schoolClass->section)
                        : 'Unassigned',
                    'advisor' => $selectedStudent->student?->schoolClass?->homeroom_teacher ?: 'Not assigned',
                ] : null,
                'courses' => $marks->count(),
                'credits' => $marks->sum(fn ($mark) => $mark['subject']['credit_hours'] ?? 0),
                'average_score' => round($marks->avg('score'), 2),
                'gpa' => round($cumulativeWeightedPoints / $totalCredits, 2),
                'passed_courses' => $marks->where('score', '>=', 40)->count(),
            ],
        ];
    }

    private function buildStudentResults(Collection $marks): Collection
    {
        return $marks
            ->groupBy('student_id')
            ->map(function (Collection $studentMarks) {
                $student = $studentMarks->first()['student'];
                $totalCredits = max((int) $studentMarks->sum(fn ($mark) => $mark['subject']['credit_hours'] ?? 0), 1);
                $weightedPoints = $studentMarks->sum('weighted_points');

                $semesters = $studentMarks
                    ->groupBy(fn ($mark) => $mark['academic_year'].'|'.$mark['semester'])
                    ->map(function (Collection $semesterMarks, string $key) {
                        [$academicYear, $semester] = explode('|', $key);
                        $credits = $semesterMarks->sum(fn ($mark) => $mark['subject']['credit_hours'] ?? 0);
                        $totalCredits = max((int) $credits, 1);
                        $weightedPoints = $semesterMarks->sum('weighted_points');

                        return [
                            'academic_year' => $academicYear,
                            'semester' => $semester,
                            'gpa' => round($weightedPoints / $totalCredits, 2),
                            'average_score' => round($semesterMarks->avg('score'), 2),
                            'credits' => $credits,
                            'courses' => $semesterMarks
                                ->sortBy('subject.name')
                                ->map(fn ($mark) => [
                                    'id' => $mark['id'],
                                    'subject_name' => $mark['subject']['name'] ?? '-',
                                    'subject_code' => $mark['subject']['code'],
                                    'credit_hours' => $mark['subject']['credit_hours'],
                                    'score' => $mark['score'],
                                    'letter_grade' => $mark['letter_grade'],
                                    'grade_points' => $mark['grade_points'],
                                    'status_label' => $mark['status_label'],
                                    'semester' => $mark['semester'],
                                    'academic_year' => $mark['academic_year'],
                                    'student_id' => $mark['student_id'],
                                    'subject_id' => $mark['subject_id'],
                                ])
                                ->values(),
                        ];
                    })
                    ->sortBy([
                        ['academic_year', 'desc'],
                        ['semester', 'asc'],
                    ])
                    ->values();

                return [
                    'student' => $student,
                    'cumulative_gpa' => round($weightedPoints / $totalCredits, 2),
                    'cumulative_average' => round($studentMarks->avg('score'), 2),
                    'total_credits' => $studentMarks->sum(fn ($mark) => $mark['subject']['credit_hours'] ?? 0),
                    'total_courses' => $studentMarks->count(),
                    'semesters' => $semesters,
                ];
            })
            ->sortBy(fn ($entry) => strtolower($entry['student']['name'] ?? ''))
            ->values();
    }

    private function buildReportData(Collection $currentMarks, Collection $allStudentMarks, ?User $selectedStudent): ?array
    {
        if (! $selectedStudent || $currentMarks->isEmpty()) {
            return null;
        }

        $currentCredits = $currentMarks->sum(fn ($mark) => $mark['subject']['credit_hours'] ?? 0);
        $currentGpEarned = round($currentMarks->sum('weighted_points'), 2);
        $allCredits = $allStudentMarks->sum(fn ($mark) => $mark['subject']['credit_hours'] ?? 0);
        $allGpEarned = round($allStudentMarks->sum('weighted_points'), 2);

        $previousCredits = max($allCredits - $currentCredits, 0);
        $previousGpEarned = round(max($allGpEarned - $currentGpEarned, 0), 2);

        return [
            'student' => [
                'name' => $selectedStudent->name,
                'email' => $selectedStudent->email,
                'department' => $selectedStudent->student?->department,
                'university' => $selectedStudent->student?->university,
                'program' => $selectedStudent->student?->schoolClass
                    ? trim($selectedStudent->student->schoolClass->name.' '.$selectedStudent->student->schoolClass->section)
                    : 'Unassigned',
            ],
            'current' => [
                'courses' => $currentMarks->values(),
                'credit_taken' => $currentCredits,
                'gp_earned' => $currentGpEarned,
                'sgpa' => round($currentGpEarned / max($currentCredits, 1), 2),
                'cgpa' => round($allGpEarned / max($allCredits, 1), 2),
                'status' => $this->academicStatus(round($allGpEarned / max($allCredits, 1), 2)),
            ],
            'previous' => [
                'credit_taken' => $previousCredits,
                'gp_earned' => $previousGpEarned,
                'cgpa' => round($previousGpEarned / max($previousCredits, 1), 2),
                'status' => $this->academicStatus(round($previousGpEarned / max($previousCredits, 1), 2)),
            ],
            'cumulative' => [
                'credit_taken' => $allCredits,
                'gp_earned' => $allGpEarned,
                'cgpa' => round($allGpEarned / max($allCredits, 1), 2),
                'status' => $this->academicStatus(round($allGpEarned / max($allCredits, 1), 2)),
            ],
        ];
    }

    private function academicStatus(float $gpa): string
    {
        return match (true) {
            $gpa >= 3.5 => "Dean's List",
            $gpa >= 2.0 => 'Good Standing',
            default => 'Probation',
        };
    }

    private function mapMark(Mark $mark): array
    {
        return [
            'id' => $mark->id,
            'student_id' => $mark->student_id,
            'subject_id' => $mark->subject_id,
            'score' => $mark->score,
            'semester' => $this->normalizeSemesterLabel($mark->semester ?: $mark->term ?: 'Semester 1'),
            'academic_year' => $mark->academic_year ?: now()->format('Y').'/'.now()->addYear()->format('Y'),
            'student' => $mark->student ? [
                'id' => $mark->student->id,
                'name' => $mark->student->name,
                'email' => $mark->student->email,
            ] : null,
            'subject' => $mark->subject ? [
                'id' => $mark->subject->id,
                'name' => $mark->subject->name,
                'code' => $mark->subject->code,
                'credit_hours' => $mark->subject->credit_hours,
            ] : null,
            'letter_grade' => $mark->letter_grade,
            'grade_points' => $mark->grade_points,
            'status_label' => $mark->status_label,
            'weighted_points' => round($mark->grade_points * ($mark->subject->credit_hours ?? 0), 2),
        ];
    }

    private function normalizeSemesterLabel(string $semester): string
    {
        return self::SEMESTER_LABELS[$semester] ?? $semester;
    }
}
