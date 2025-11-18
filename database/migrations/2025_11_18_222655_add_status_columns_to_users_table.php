<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('account_status_id')->nullable()->after('email_verified_at')->constrained('account_statuses')->onDelete('set null');
            $table->foreignId('user_status_id')->nullable()->after('account_status_id')->constrained('user_statuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['account_status_id']);
            $table->dropForeign(['user_status_id']);
            $table->dropColumn(['account_status_id', 'user_status_id']);
        });
    }
};
