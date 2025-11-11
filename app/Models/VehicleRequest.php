<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class VehicleRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'requested_departure_date',
        'requested_return_date',
        'description',
        'destination',
        'event',
        'request_status_id',
        'approval_date',
        'approved_by',
        'approval_note',
        'creation_date',
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
            'user_id' => 'integer',
            'vehicle_id' => 'integer',
            'requested_departure_date' => 'datetime',
            'requested_return_date' => 'datetime',
            'request_status_id' => 'integer',
            'approval_date' => 'datetime',
            'approved_by' => 'integer',
            'creation_date' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function requestStatus(): BelongsTo
    {
        return $this->belongsTo(RequestStatus::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function usageHistories(): HasMany
    {
        return $this->hasMany(VehicleUsageHistory::class, 'request_id');
    }

    /**
     * Validate that the vehicle is available for the requested dates
     * 
     * @param int $vehicleId
     * @param \Carbon\Carbon $departureDate
     * @param \Carbon\Carbon $returnDate
     * @param int|null $excludeRequestId Request ID to exclude from check (when editing)
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validateVehicleAvailability($vehicleId, $departureDate, $returnDate, $excludeRequestId = null): bool
    {
        // Ensure dates are Carbon instances
        if (!$departureDate instanceof Carbon) {
            $departureDate = Carbon::parse($departureDate);
        }
        if (!$returnDate instanceof Carbon) {
            $returnDate = Carbon::parse($returnDate);
        }

        $vehicle = Vehicle::find($vehicleId);
        
        if (!$vehicle) {
            throw ValidationException::withMessages([
                'vehicle_id' => ['El vehículo seleccionado no existe.'],
            ]);
        }

        // Check if vehicle is available for the dates
        if (!$vehicle->isAvailableForDates($departureDate, $returnDate, $excludeRequestId)) {
            throw ValidationException::withMessages([
                'vehicle_id' => ['El vehículo no está disponible para las fechas seleccionadas. Por favor, seleccione otras fechas.'],
            ]);
        }

        return true;
    }

    /**
     * Validate that user doesn't have duplicate pending requests for the same vehicle and dates
     * 
     * @param int $userId
     * @param int $vehicleId
     * @param \Carbon\Carbon $departureDate
     * @param \Carbon\Carbon $returnDate
     * @param int|null $excludeRequestId Request ID to exclude from check (when editing)
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validateNoDuplicatePendingRequests($userId, $vehicleId, $departureDate, $returnDate, $excludeRequestId = null): bool
    {
        // Ensure dates are Carbon instances
        if (!$departureDate instanceof Carbon) {
            $departureDate = Carbon::parse($departureDate);
        }
        if (!$returnDate instanceof Carbon) {
            $returnDate = Carbon::parse($returnDate);
        }

        $pendingStatus = RequestStatus::where('name', 'Pending')->first();
        
        if (!$pendingStatus) {
            return true; // If no pending status exists, skip this check
        }

        $existingRequest = self::where('user_id', $userId)
            ->where('vehicle_id', $vehicleId)
            ->where('request_status_id', $pendingStatus->id)
            ->where(function ($query) use ($departureDate, $returnDate) {
                // Check for overlapping dates
                $query->where(function ($q) use ($departureDate, $returnDate) {
                    $q->where('requested_departure_date', '<', $returnDate)
                      ->where('requested_return_date', '>', $departureDate);
                });
            })
            ->when($excludeRequestId, function ($q) use ($excludeRequestId) {
                $q->where('id', '!=', $excludeRequestId);
            })
            ->first();

        if ($existingRequest) {
            throw ValidationException::withMessages([
                'vehicle_id' => ['Ya tiene una solicitud pendiente para este vehículo en las fechas seleccionadas. Por favor, espere la aprobación o seleccione otras fechas.'],
            ]);
        }

        return true;
    }

    /**
     * Validate that dates are not in the past
     * 
     * @param \Carbon\Carbon $departureDate
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validateDatesNotInPast($departureDate): bool
    {
        // Ensure date is Carbon instance
        if (!$departureDate instanceof Carbon) {
            $departureDate = Carbon::parse($departureDate);
        }

        if ($departureDate < now()) {
            throw ValidationException::withMessages([
                'requested_departure_date' => ['La fecha de salida no puede ser en el pasado.'],
            ]);
        }

        return true;
    }

    /**
     * Validate that return date is after departure date
     * 
     * @param \Carbon\Carbon|string $departureDate
     * @param \Carbon\Carbon|string $returnDate
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validateReturnDateAfterDeparture($departureDate, $returnDate): bool
    {
        // Ensure dates are Carbon instances
        if (!$departureDate instanceof Carbon) {
            $departureDate = Carbon::parse($departureDate);
        }
        if (!$returnDate instanceof Carbon) {
            $returnDate = Carbon::parse($returnDate);
        }

        if ($returnDate <= $departureDate) {
            throw ValidationException::withMessages([
                'requested_return_date' => ['La fecha de retorno debe ser posterior a la fecha de salida.'],
            ]);
        }

        return true;
    }

    /**
     * Validate date range is reasonable (max 90 days)
     * 
     * @param \Carbon\Carbon|string $departureDate
     * @param \Carbon\Carbon|string $returnDate
     * @param int $maxDays Maximum number of days allowed
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validateDateRange($departureDate, $returnDate, $maxDays = 90): bool
    {
        // Ensure dates are Carbon instances
        if (!$departureDate instanceof Carbon) {
            $departureDate = Carbon::parse($departureDate);
        }
        if (!$returnDate instanceof Carbon) {
            $returnDate = Carbon::parse($returnDate);
        }

        $daysDifference = $departureDate->diffInDays($returnDate);
        
        if ($daysDifference > $maxDays) {
            throw ValidationException::withMessages([
                'requested_return_date' => ["El rango de fechas no puede exceder {$maxDays} días."],
            ]);
        }

        return true;
    }
}
