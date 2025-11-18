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
        Schema::table('vehicle_requests', function (Blueprint $table) {
            // Eliminar la foreign key constraint incorrecta
            $table->dropForeign(['approved_by']);
            
            // Recrear la foreign key constraint correcta apuntando a 'users'
            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_requests', function (Blueprint $table) {
            // Eliminar la foreign key correcta
            $table->dropForeign(['approved_by']);
            
            // Recrear la foreign key original (incorrecta) para poder revertir
            $table->foreign('approved_by')
                ->references('id')
                ->on('user')
                ->onDelete('set null');
        });
    }
};
