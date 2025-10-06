<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\FuelType;
use App\Models\Vehicle;
use App\Models\VehicleStatus;

class VehicleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vehicle::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'brand' => fake()->regexify('[A-Za-z0-9]{100}'),
            'model' => fake()->regexify('[A-Za-z0-9]{100}'),
            'year' => fake()->numberBetween(-10000, 10000),
            'plate' => fake()->regexify('[A-Za-z0-9]{20}'),
            'vin' => fake()->regexify('[A-Za-z0-9]{50}'),
            'fuel_type_id' => FuelType::factory(),
            'current_mileage' => fake()->numberBetween(-10000, 10000),
            'status_id' => VehicleStatus::factory(),
            'current_location' => fake()->regexify('[A-Za-z0-9]{255}'),
            'note' => fake()->text(),
            'registration_date' => fake()->dateTime(),
            'belongsTo' => fake()->word(),
        ];
    }
}
