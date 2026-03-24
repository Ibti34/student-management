<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->unsignedTinyInteger('credit_hours')->default(3)->after('code');
        });

        Schema::table('marks', function (Blueprint $table) {
            $table->string('semester')->default('Semester 1')->after('score');
            $table->string('academic_year')->default(date('Y').'/'.(date('Y') + 1))->after('semester');
        });

        DB::table('marks')
            ->select('id', 'term')
            ->orderBy('id')
            ->chunkById(100, function ($marks) {
                foreach ($marks as $mark) {
                    DB::table('marks')
                        ->where('id', $mark->id)
                        ->update([
                            'semester' => $mark->term ?: 'Semester 1',
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->dropColumn(['semester', 'academic_year']);
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('credit_hours');
        });
    }
};
