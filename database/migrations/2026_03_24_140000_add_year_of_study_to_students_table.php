<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedTinyInteger('year_of_study')->nullable()->after('department');
        });

        $students = DB::table('students')
            ->leftJoin('school_classes', 'school_classes.id', '=', 'students.school_class_id')
            ->select('students.id', 'school_classes.section')
            ->get();

        foreach ($students as $student) {
            if (! $student->section || ! preg_match('/Year\s+(\d+)/i', $student->section, $matches)) {
                continue;
            }

            DB::table('students')
                ->where('id', $student->id)
                ->update(['year_of_study' => (int) $matches[1]]);
        }
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('year_of_study');
        });
    }
};
