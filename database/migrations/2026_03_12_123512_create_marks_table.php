<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            // Link to the user who is the student
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            // Link to the actual subject record
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->integer('score');
            $table->string('term')->default('Term 1');
            // Who gave the mark
            $table->foreignId('teacher_id')->constrained('users');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
