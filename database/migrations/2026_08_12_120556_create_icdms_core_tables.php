<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Departments
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Extend Users table for ICDMS Staff/Public Users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('phone')->nullable();
            $table->enum('user_type', ['staff', 'volunteer', 'beneficiary', 'partner', 'donor'])->default('staff');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
        });

        // Communities
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('state');
            $table->string('lga');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('estimated_population')->default(0);
            $table->json('needs_assessment')->nullable();
            $table->timestamps();
        });

        // Programs
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('program_code')->unique();
            $table->string('title');
            $table->text('description');
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('budget', 15, 2)->default(0.00);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['planning', 'active', 'suspended', 'completed'])->default('planning');
            $table->timestamps();
        });

        // Projects
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code')->unique();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('community_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('objectives')->nullable();
            $table->decimal('budget', 15, 2)->default(0.00);
            $table->decimal('expenditure', 15, 2)->default(0.00);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['draft', 'approved', 'in_progress', 'on_hold', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
        });

        // Beneficiaries (ICDMS v4)
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('beneficiary_code')->unique();
            $table->string('full_name');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->integer('age');
            $table->string('phone')->nullable();
            $table->foreignId('community_id')->constrained()->cascadeOnDelete();
            $table->string('category'); // e.g., Youth, Women, Entrepreneur
            $table->timestamps();
        });

        // KPIs (ICDMS v9 M&E Engine)
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('unit'); // e.g., "Persons Trained", "Grants Issued"
            $table->decimal('baseline', 12, 2)->default(0);
            $table->decimal('target', 12, 2);
            $table->decimal('current', 12, 2)->default(0);
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'quarterly', 'annually']);
            $table->foreignId('assigned_officer_id')->nullable()->constrained('users');
            $table->timestamps();
        });

        // Monitoring Visits (ICDMS v9 Field Engine)
        Schema::create('field_monitoring_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('field_officer_id')->constrained('users');
            $table->date('visit_date');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('observations');
            $table->text('challenges')->nullable();
            $table->text('recommendations')->nullable();
            $table->enum('status', ['scheduled', 'conducted', 'reviewed', 'approved'])->default('scheduled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_monitoring_visits');
        Schema::dropIfExists('kpis');
        Schema::dropIfExists('beneficiaries');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('communities');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'phone', 'user_type', 'is_active', 'last_login_at']);
        });
        Schema::dropIfExists('departments');
    }
};