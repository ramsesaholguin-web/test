<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleStatus;
use App\Models\FuelType;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the "Active" status
        $activeStatus = VehicleStatus::where('name', 'Active')->first();
        
        // Get the first fuel type (or create a default one)
        $fuelType = FuelType::first();
        
        if (!$activeStatus) {
            $this->command->error('VehicleStatus "Active" not found. Please run VehicleStatusSeeder first.');
            return;
        }
        
        if (!$fuelType) {
            $this->command->error('No FuelType found. Please run FuelTypeSeeder first.');
            return;
        }

        // Create 5 vehicles with Active status
        $vehicles = [
            [
                'brand' => 'Toyota',
                'model' => 'Camry',
                'year' => 2022,
                'plate' => 'ABC-1234',
                'vin' => '1HGBH41JXMN109186',
                'fuel_type_id' => $fuelType->id,
                'current_mileage' => 15000,
                'status_id' => $activeStatus->id,
                'current_location' => 'Main Office',
                'note' => 'Company vehicle - well maintained',
                'registration_date' => now()->subYear(),
                'belongsTo' => 'Company',
            ],
            [
                'brand' => 'Honda',
                'model' => 'Civic',
                'year' => 2021,
                'plate' => 'XYZ-5678',
                'vin' => '2HGFB2F59MH543210',
                'fuel_type_id' => $fuelType->id,
                'current_mileage' => 25000,
                'status_id' => $activeStatus->id,
                'current_location' => 'Main Office',
                'note' => 'Efficient and reliable',
                'registration_date' => now()->subYears(2),
                'belongsTo' => 'Company',
            ],
            [
                'brand' => 'Ford',
                'model' => 'F-150',
                'year' => 2023,
                'plate' => 'DEF-9012',
                'vin' => '1FTFW1ET5NFA12345',
                'fuel_type_id' => $fuelType->id,
                'current_mileage' => 5000,
                'status_id' => $activeStatus->id,
                'current_location' => 'Warehouse',
                'note' => 'New truck for deliveries',
                'registration_date' => now()->subMonths(6),
                'belongsTo' => 'Company',
            ],
            [
                'brand' => 'Nissan',
                'model' => 'Altima',
                'year' => 2020,
                'plate' => 'GHI-3456',
                'vin' => '1N4AL3AP8LC123456',
                'fuel_type_id' => $fuelType->id,
                'current_mileage' => 35000,
                'status_id' => $activeStatus->id,
                'current_location' => 'Main Office',
                'note' => 'Good condition, regular maintenance',
                'registration_date' => now()->subYears(3),
                'belongsTo' => 'Company',
            ],
            [
                'brand' => 'Chevrolet',
                'model' => 'Malibu',
                'year' => 2022,
                'plate' => 'JKL-7890',
                'vin' => '1G1ZD5ST7NF123789',
                'fuel_type_id' => $fuelType->id,
                'current_mileage' => 12000,
                'status_id' => $activeStatus->id,
                'current_location' => 'Main Office',
                'note' => 'Comfortable sedan for executives',
                'registration_date' => now()->subMonths(10),
                'belongsTo' => 'Company',
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            // Check if vehicle already exists by plate or VIN
            $existing = Vehicle::where('plate', $vehicleData['plate'])
                ->orWhere('vin', $vehicleData['vin'])
                ->first();
            
            if (!$existing) {
                Vehicle::create($vehicleData);
            } else {
                // Update existing vehicle to ensure it has Active status
                $existing->update([
                    'status_id' => $activeStatus->id,
                ]);
                $this->command->info("Vehicle with plate {$vehicleData['plate']} already exists. Updated status to Active.");
            }
        }

        $this->command->info('Created ' . count($vehicles) . ' vehicles successfully!');
    }
}

