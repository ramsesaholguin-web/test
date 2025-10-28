<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleUsageHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'departure_mileage',
        'departure_fuel_level',
        'actual_departure_date',
        'departure_note',
        'return_mileage',
        'return_fuel_level',
        'actual_return_date',
        'return_note',
        'belongsTo',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'request_id' => 'integer',
            'departure_fuel_level' => 'decimal:2',
            'actual_departure_date' => 'timestamp',
            'return_fuel_level' => 'decimal:2',
            'actual_return_date' => 'timestamp',
        ];
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(VehicleRequest::class);
    }

    public function historyEvidences(): HasMany
    {
        return $this->hasMany(HistoryEvidence::class, 'history_id');
    }
}
