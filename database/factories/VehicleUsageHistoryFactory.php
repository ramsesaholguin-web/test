<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\VehicleRequest;
use App\Models\VehicleUsageHistory;

class VehicleUsageHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VehicleUsageHistory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'request_id' => VehicleRequest::factory(),
            'departure_mileage' => fake()->numberBetween(-10000, 10000),
            'departure_fuel_level' => fake()->randomFloat(2, 0, 999.99),
            'actual_departure_date' => fake()->dateTime(),
            'departure_note' => fake()->text(),
            'return_mileage' => fake()->numberBetween(-10000, 10000),
            'return_fuel_level' => fake()->randomFloat(2, 0, 999.99),
            'actual_return_date' => fake()->dateTime(),
            'return_note' => fake()->text(),
            'belongsTo' => fake()->word(),
        ];
    }
}
