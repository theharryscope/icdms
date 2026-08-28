<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo_path')->nullable()->after('document_path');
            $table->boolean('privacy_policy_accepted')->default(false)->after('profile_photo_path');
            $table->timestamp('privacy_policy_accepted_at')->nullable()->after('privacy_policy_accepted');
            $table->boolean('terms_accepted')->default(false)->after('privacy_policy_accepted_at');
            $table->timestamp('terms_accepted_at')->nullable()->after('terms_accepted');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo_path',
                'privacy_policy_accepted',
                'privacy_policy_accepted_at',
                'terms_accepted',
                'terms_accepted_at',
            ]);
        });
    }
};