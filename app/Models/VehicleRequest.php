<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
