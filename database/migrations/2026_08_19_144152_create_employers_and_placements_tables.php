<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Corporate Employer Profiles
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Linked Employer User Account
            $table->string('company_name');
            $table->string('industry_sector')->nullable();
            $table->string('contact_person_name');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->string('office_address')->nullable();
            $table->timestamps();
        });

        // 2. Student Company Placements & Feedback
        Schema::create('student_placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('employer_id')->constrained('employers')->onDelete('cascade');
            $table->string('job_title');
            $table->date('placement_date');
            $table->string('employment_status')->default('active'); // active, completed, terminated
            $table->text('employer_feedback')->nullable();
            $table->integer('performance_rating')->nullable(); // 1 to 5 Stars
            $table->timestamp('feedback_submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_placements');
        Schema::dropIfExists('employers');
    }
};