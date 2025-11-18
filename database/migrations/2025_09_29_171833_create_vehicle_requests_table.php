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
        Schema::disableForeignKeyConstraints();

        Schema::create('vehicle_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('vehicle_id')->constrained();
            $table->timestamp('requested_departure_date');
            $table->timestamp('requested_return_date');
            $table->text('description')->nullable();
            $table->string('destination', 255)->nullable();
            $table->string('event', 255)->nullable();
            $table->foreignId('request_status_id')->constrained();
            $table->timestamp('approval_date')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('approval_note')->nullable();
            $table->timestamp('creation_date')->useCurrent();
            $table->string('belongsTo');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_requests');
    }
};
