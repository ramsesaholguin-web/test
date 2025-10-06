<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryEvidence extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'history_id',
        'evidence_type_id',
        'url',
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
            'history_id' => 'integer',
            'evidence_type_id' => 'integer',
        ];
    }

    public function history(): BelongsTo
    {
        return $this->belongsTo(VehicleUsageHistory::class);
    }

    public function evidenceType(): BelongsTo
    {
        return $this->belongsTo(EvidenceType::class);
    }
}
