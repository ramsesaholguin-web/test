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

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('brand', 100);
            $table->string('model', 100);
            $table->integer('year');
            $table->string('plate', 20)->unique();
            $table->string('vin', 50)->unique();
            $table->foreignId('fuel_type_id')->constrained();
            $table->integer('current_mileage');
            $table->foreignId('status_id')->constrained('vehicle_statuses');
            $table->string('current_location', 255)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('registration_date')->useCurrent();
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
        Schema::dropIfExists('vehicles');
    }
};
