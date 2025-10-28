<?php

namespace Database\Seeders;

use App\Models\RequestStatus;
use Illuminate\Database\Seeder;

class RequestStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requestStatuses = [
            ['name' => 'Pending'],
            ['name' => 'Approved'],
            ['name' => 'Rejected'],
            ['name' => 'In Progress'],
            ['name' => 'Completed'],
            ['name' => 'Cancelled'],
        ];

        foreach ($requestStatuses as $status) {
            RequestStatus::create($status);
        }
    }
}