<?php

namespace Database\Seeders;

use App\Models\MaintenanceType;
use Illuminate\Database\Seeder;

class MaintenanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maintenanceTypes = [
            ['name' => 'Oil Change'],
            ['name' => 'Brake Service'],
            ['name' => 'Tire Rotation'],
            ['name' => 'Engine Tune-up'],
            ['name' => 'Transmission Service'],
            ['name' => 'Air Filter Replacement'],
            ['name' => 'Battery Check'],
            ['name' => 'General Inspection'],
        ];

        foreach ($maintenanceTypes as $type) {
            MaintenanceType::create($type);
        }
    }
}