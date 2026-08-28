<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('student_category')->default('learning'); // 'learning' or 'certified_placed'
            $table->string('learning_track')->nullable(); // e.g. Full-Stack Web, Mobile Dev, UI/UX
            $table->integer('course_progress_percentage')->default(0);
            $table->date('certification_date')->nullable();
            $table->string('placed_company_name')->nullable();
            $table->string('placed_job_title')->nullable();
            $table->date('placement_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'student_category',
                'learning_track',
                'course_progress_percentage',
                'certification_date',
                'placed_company_name',
                'placed_job_title',
                'placement_date',
            ]);
        });
    }
};