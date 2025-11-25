<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed lookup tables
        $this->call([
            VehicleStatusSeeder::class,
            FuelTypeSeeder::class,
            MaintenanceTypeSeeder::class,
            RequestStatusSeeder::class,
            VehicleSeeder::class,
            RolesAndPermissionsSeeder::class, // Roles y permisos
        ]);
    }
}
