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

        Schema::create('vehicle_usage_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('vehicle_request');
            $table->integer('departure_mileage');
            $table->decimal('departure_fuel_level', 5, 2);
            $table->timestamp('actual_departure_date');
            $table->text('departure_note')->nullable();
            $table->integer('return_mileage')->nullable();
            $table->decimal('return_fuel_level', 5, 2)->nullable();
            $table->timestamp('actual_return_date')->nullable();
            $table->text('return_note')->nullable();
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
        Schema::dropIfExists('vehicle_usage_histories');
    }
};
