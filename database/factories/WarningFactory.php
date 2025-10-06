<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Warning;
use App\Models\WarningType;

class WarningFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Warning::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'warning_date' => fake()->dateTime(),
            'warning_type_id' => WarningType::factory(),
            'description' => fake()->text(),
            'evidence_url' => fake()->regexify('[A-Za-z0-9]{255}'),
            'warned_by' => User::factory(),
            'belongsTo' => fake()->word(),
        ];
    }
}
