<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\RequestStatus;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleRequest;

class VehicleRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VehicleRequest::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'vehicle_id' => Vehicle::factory(),
            'requested_departure_date' => fake()->dateTime(),
            'requested_return_date' => fake()->dateTime(),
            'description' => fake()->text(),
            'destination' => fake()->regexify('[A-Za-z0-9]{255}'),
            'event' => fake()->regexify('[A-Za-z0-9]{255}'),
            'request_status_id' => RequestStatus::factory(),
            'approval_date' => fake()->dateTime(),
            'approved_by' => User::factory(),
            'approval_note' => fake()->text(),
            'creation_date' => fake()->dateTime(),
            'belongsTo' => fake()->word(),
        ];
    }
}
