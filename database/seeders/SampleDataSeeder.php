<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\User;
use App\Models\Maintenance;
use App\Models\VehicleRequest;
use App\Models\VehicleDocument;
use App\Models\Warning;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first vehicle and user for relationships
        $vehicle = Vehicle::first();
        $user = User::first();

        if (!$vehicle || !$user) {
            $this->command->info('No vehicle or user found. Please create them first.');
            return;
        }

        // Create sample maintenance records
        Maintenance::create([
            'vehicle_id' => $vehicle->id,
            'maintenance_type_id' => 1, // Oil Change
            'maintenance_date' => now()->subDays(30),
            'maintenance_mileage' => 15000,
            'cost' => 45.00,
            'workshop' => 'AutoCare Center',
            'note' => 'Regular oil change service',
            'next_maintenance_mileage' => 18000,
            'next_maintenance_date' => now()->addDays(90),
            'belongsTo' => 'Admin',
        ]);

        Maintenance::create([
            'vehicle_id' => $vehicle->id,
            'maintenance_type_id' => 2, // Brake Service
            'maintenance_date' => now()->subDays(15),
            'maintenance_mileage' => 16000,
            'cost' => 120.00,
            'workshop' => 'Brake Masters',
            'note' => 'Brake pad replacement',
            'next_maintenance_mileage' => 25000,
            'next_maintenance_date' => now()->addDays(180),
            'belongsTo' => 'Admin',
        ]);

        // Create sample vehicle request records
        VehicleRequest::create([
            'user_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'requested_departure_date' => now()->addDays(7),
            'requested_return_date' => now()->addDays(10),
            'description' => 'Business trip to client meeting',
            'destination' => 'Downtown Office',
            'event' => 'Client Meeting',
            'request_status_id' => 1, // Pending
            'creation_date' => now(),
            'belongsTo' => 'Admin',
        ]);

        VehicleRequest::create([
            'user_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'requested_departure_date' => now()->addDays(14),
            'requested_return_date' => now()->addDays(16),
            'description' => 'Team building event',
            'destination' => 'Mountain Resort',
            'event' => 'Team Building',
            'request_status_id' => 2, // Approved
            'creation_date' => now()->subDays(2),
            'belongsTo' => 'Admin',
        ]);

        // Create sample vehicle document records
        VehicleDocument::create([
            'vehicle_id' => $vehicle->id,
            'document_name' => 'Insurance Certificate',
            'file_path' => 'documents/insurance_cert.pdf',
            'expiration_date' => now()->addDays(365),
            'upload_date' => now()->subDays(30),
            'uploaded_by' => $user->id,
            'belongsTo' => 'Admin',
        ]);

        VehicleDocument::create([
            'vehicle_id' => $vehicle->id,
            'document_name' => 'Registration Document',
            'file_path' => 'documents/registration.pdf',
            'expiration_date' => now()->addDays(180),
            'upload_date' => now()->subDays(15),
            'uploaded_by' => $user->id,
            'belongsTo' => 'Admin',
        ]);

        // Create sample warning records
        Warning::create([
            'user_id' => $user->id,
            'warning_type_id' => 1, // Assuming first warning type
            'warning_date' => now()->subDays(5),
            'description' => 'Late arrival to work',
            'evidence_url' => 'https://example.com/evidence1',
            'warned_by' => $user->id,
            'belongsTo' => 'Admin',
        ]);

        Warning::create([
            'user_id' => $user->id,
            'warning_type_id' => 1, // Assuming first warning type
            'warning_date' => now()->subDays(10),
            'description' => 'Incomplete project submission',
            'evidence_url' => 'https://example.com/evidence2',
            'warned_by' => $user->id,
            'belongsTo' => 'Admin',
        ]);

        $this->command->info('Sample data created successfully!');
    }
}