<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Volunteer who logged it
            $table->foreignId('community_id')->constrained()->onDelete('cascade');
            $table->string('activity_title');
            $table->decimal('hours_spent', 5, 2)->default(0);
            $table->string('teaching_topic')->nullable();
            $table->integer('attendees_count')->default(0);
            $table->text('activity_notes');
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->text('coordinator_remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_activity_logs');
    }
};