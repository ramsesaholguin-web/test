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

        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained();
            $table->foreignId('maintenance_type_id')->constrained();
            $table->timestamp('maintenance_date');
            $table->integer('maintenance_mileage');
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('workshop', 255)->nullable();
            $table->text('note')->nullable();
            $table->integer('next_maintenance_mileage')->nullable();
            $table->timestamp('next_maintenance_date')->nullable();
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
        Schema::dropIfExists('maintenances');
    }
};
