<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('student_id_number')->nullable()->unique();
            $table->string('enrolled_course_title')->nullable(); // e.g. Full-Stack Web Development, Data Science
            $table->string('cohort_batch')->nullable(); // e.g. Batch A 2026
            $table->integer('attendance_percentage')->default(100);
            $table->integer('course_progress')->default(0); // 0-100%
            $table->string('student_status')->default('active'); // active, graduated, dropped
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'student_id_number',
                'enrolled_course_title',
                'cohort_batch',
                'attendance_percentage',
                'course_progress',
                'student_status',
            ]);
        });
    }
};