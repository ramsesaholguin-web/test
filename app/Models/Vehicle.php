<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand',
        'model',
        'year',
        'plate',
        'vin',
        'fuel_type_id',
        'current_mileage',
        'status_id',
        'current_location',
        'note',
        'registration_date',
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
            'fuel_type_id' => 'integer',
            'status_id' => 'integer',
            'registration_date' => 'timestamp',
        ];
    }

    public function fuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(VehicleStatus::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    public function vehicleRequests(): HasMany
    {
        return $this->hasMany(VehicleRequest::class);
    }

    public function vehicleDocuments(): HasMany
    {
        return $this->hasMany(VehicleDocument::class);
    }
}
