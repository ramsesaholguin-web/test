<?php

namespace Database\Seeders;

use App\Models\VehicleStatus;
use Illuminate\Database\Seeder;

class VehicleStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'New'],
            ['name' => 'Active'],
            ['name' => 'Maintenance'],
            ['name' => 'Retired'],
            ['name' => 'Sold'],
        ];

        foreach ($statuses as $status) {
            VehicleStatus::create($status);
        }
    }
}