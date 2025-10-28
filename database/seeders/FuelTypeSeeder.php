<?php

namespace Database\Seeders;

use App\Models\FuelType;
use Illuminate\Database\Seeder;

class FuelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fuelTypes = [
            ['name' => 'Gasolina'],
            ['name' => 'Diesel'],
            ['name' => 'Eléctrico'],
            ['name' => 'Híbrido'],
            ['name' => 'Gas Natural'],
        ];

        foreach ($fuelTypes as $fuelType) {
            FuelType::create($fuelType);
        }
    }
}