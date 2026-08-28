<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique(); // e.g. DON-2026-X891
            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('donor_phone')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('payment_method'); // 'paystack' or 'bank_transfer'
            $table->string('payment_status')->default('pending'); // 'pending', 'successful', 'failed'
            $table->string('paystack_reference')->nullable();
            $table->string('proof_of_payment_path')->nullable(); // Uploaded transfer receipt path
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};