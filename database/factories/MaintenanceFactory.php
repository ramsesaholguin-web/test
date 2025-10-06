<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Maintenance;
use App\Models\MaintenanceType;
use App\Models\Vehicle;

class MaintenanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Maintenance::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'vehicle_id' => Vehicle::factory(),
            'maintenance_type_id' => MaintenanceType::factory(),
            'maintenance_date' => fake()->dateTime(),
            'maintenance_mileage' => fake()->numberBetween(-10000, 10000),
            'cost' => fake()->randomFloat(2, 0, 99999999.99),
            'workshop' => fake()->regexify('[A-Za-z0-9]{255}'),
            'note' => fake()->text(),
            'next_maintenance_mileage' => fake()->numberBetween(-10000, 10000),
            'next_maintenance_date' => fake()->dateTime(),
            'belongsTo' => fake()->word(),
        ];
    }
}
