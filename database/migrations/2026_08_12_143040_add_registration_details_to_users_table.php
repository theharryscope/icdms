<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('registration_role')->default('volunteer'); // volunteer, coordinator, partner, student
            $table->string('organization_name')->nullable();
            $table->string('qualification_degree')->nullable();
            $table->text('skills_and_expertise')->nullable();
            $table->text('motivation_statement')->nullable();
            $table->string('document_path')->nullable(); // Uploaded CV / Org Credentials
            $table->string('application_status')->default('pending'); // pending, approved, rejected
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'registration_role',
                'organization_name',
                'qualification_degree',
                'skills_and_expertise',
                'motivation_statement',
                'document_path',
                'application_status'
            ]);
        });
    }
};