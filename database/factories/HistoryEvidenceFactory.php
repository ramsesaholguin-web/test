<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\EvidenceType;
use App\Models\HistoryEvidence;
use App\Models\VehicleUsageHistory;

class HistoryEvidenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HistoryEvidence::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'history_id' => VehicleUsageHistory::factory(),
            'evidence_type_id' => EvidenceType::factory(),
            'url' => fake()->url(),
            'belongsTo' => fake()->word(),
        ];
    }
}
