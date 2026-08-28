<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Zones
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g. South-East Zone
            $table->string('code')->unique(); // e.g. SEZ
            $table->text('description')->nullable();
            $table->foreignId('zonal_coordinator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 2. States within Zones
        Schema::create('zone_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('zones')->cascadeOnDelete();
            $table->string('name'); // e.g. Anambra State
            $table->foreignId('state_coordinator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Local Government Areas (LGAs) within States
        Schema::create('local_governments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_state_id')->constrained('zone_states')->cascadeOnDelete();
            $table->string('name'); // e.g. Awka South
            $table->foreignId('lga_coordinator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('project_leader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 4. Attach region scopes to Users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('zone_id')->nullable()->constrained('zones')->nullOnDelete();
            $table->foreignId('zone_state_id')->nullable()->constrained('zone_states')->nullOnDelete();
            $table->foreignId('local_government_id')->nullable()->constrained('local_governments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropForeign(['zone_state_id']);
            $table->dropForeign(['local_government_id']);
            $table->dropColumn(['zone_id', 'zone_state_id', 'local_government_id']);
        });
        Schema::dropIfExists('local_governments');
        Schema::dropIfExists('zone_states');
        Schema::dropIfExists('zones');
    }
};