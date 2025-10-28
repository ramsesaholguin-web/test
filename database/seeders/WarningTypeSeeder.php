<?php

namespace Database\Seeders;

use App\Models\WarningType;
use Illuminate\Database\Seeder;

class WarningTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warningTypes = [
            ['name' => 'Attendance Issue'],
            ['name' => 'Performance Warning'],
            ['name' => 'Behavior Warning'],
            ['name' => 'Policy Violation'],
            ['name' => 'Safety Concern'],
            ['name' => 'Quality Issue'],
        ];

        foreach ($warningTypes as $type) {
            WarningType::create($type);
        }
    }
}