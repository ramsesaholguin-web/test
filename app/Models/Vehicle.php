<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

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

    /**
     * Check if vehicle is available for a given date range
     * 
     * @param \Carbon\Carbon $departureDate
     * @param \Carbon\Carbon $returnDate
     * @param int|null $excludeRequestId Optional request ID to exclude from check (for editing)
     * @return bool
     */
    public function isAvailableForDates($departureDate, $returnDate, $excludeRequestId = null): bool
    {
        // Check if vehicle status is "Active" (available)
        $activeStatus = VehicleStatus::where('name', 'Active')->first();
        if (!$activeStatus || $this->status_id !== $activeStatus->id) {
            return false;
        }

        // Check for overlapping approved requests
        $approvedStatus = RequestStatus::where('name', 'Approved')->first();
        if (!$approvedStatus) {
            return true; // If no approved status exists, consider available
        }

        $query = $this->vehicleRequests()
            ->where('request_status_id', $approvedStatus->id)
            ->where('requested_return_date', '>', now()) // Only consider future/current requests
            ->where(function ($q) use ($departureDate, $returnDate) {
                $q->where(function ($subQ) use ($departureDate, $returnDate) {
                    // Overlap condition: inicio1 < fin2 AND fin1 > inicio2
                    $subQ->where('requested_departure_date', '<', $returnDate)
                         ->where('requested_return_date', '>', $departureDate);
                });
            });

        if ($excludeRequestId) {
            $query->where('id', '!=', $excludeRequestId);
        }

        return $query->count() === 0;
    }

    /**
     * Scope to get available vehicles for a date range
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $departureDate
     * @param \Carbon\Carbon $returnDate
     * @param int|null $excludeRequestId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailableForDates($query, $departureDate, $returnDate, $excludeRequestId = null)
    {
        $activeStatus = VehicleStatus::where('name', 'Active')->first();
        if (!$activeStatus) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        $approvedStatus = RequestStatus::where('name', 'Approved')->first();
        if (!$approvedStatus) {
            return $query->where('status_id', $activeStatus->id);
        }

        // Get vehicle IDs that have overlapping approved requests
        // Only consider requests that haven't ended yet (return_date > now)
        // This prevents past requests from blocking future availability
        $busyVehicleIds = VehicleRequest::where('request_status_id', $approvedStatus->id)
            ->where('requested_return_date', '>', now()) // Only consider future/current requests
            ->where(function ($q) use ($departureDate, $returnDate) {
                // Overlap condition: inicio_existente < fin_solicitada AND fin_existente > inicio_solicitada
                $q->where('requested_departure_date', '<', $returnDate)
                  ->where('requested_return_date', '>', $departureDate);
            })
            ->when($excludeRequestId, function ($q) use ($excludeRequestId) {
                $q->where('id', '!=', $excludeRequestId);
            })
            ->pluck('vehicle_id')
            ->unique();

        return $query->where('status_id', $activeStatus->id)
                     ->whereNotIn('id', $busyVehicleIds);
    }
}
