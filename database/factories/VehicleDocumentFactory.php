<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleDocument;

class VehicleDocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VehicleDocument::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'vehicle_id' => Vehicle::factory(),
            'document_name' => fake()->regexify('[A-Za-z0-9]{100}'),
            'file_path' => fake()->regexify('[A-Za-z0-9]{255}'),
            'expiration_date' => fake()->date(),
            'upload_date' => fake()->dateTime(),
            'uploaded_by' => User::factory(),
            'belongsTo' => fake()->word(),
        ];
    }
}
