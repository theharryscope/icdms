<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Donors Table
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Ford Foundation, UNDP, Local Partner
            $table->string('donor_type')->default('grant_body'); // grant_body, corporate, individual
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->default('Nigeria');
            $table->timestamps();
        });

        // 2. Grants Table
        Schema::create('grants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->string('grant_title');
            $table->string('grant_code')->unique(); // e.g. GNT-2026-001
            $table->decimal('total_amount', 15, 2);
            $table->decimal('disbursed_amount', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('active'); // active, completed, pending
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grants');
        Schema::dropIfExists('donors');
    }
};