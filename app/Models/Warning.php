<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warning extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'warning_date',
        'warning_type_id',
        'description',
        'evidence_url',
        'warned_by',
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
            'warning_date' => 'timestamp',
            'warning_type_id' => 'integer',
            'warned_by' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function warningType(): BelongsTo
    {
        return $this->belongsTo(WarningType::class);
    }

    public function warnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
