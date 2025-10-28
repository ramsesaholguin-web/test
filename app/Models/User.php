<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function warnings(): HasMany
    {
        return $this->hasMany(Warning::class);
    }

    public function vehicleDocuments(): HasMany
    {
        return $this->hasMany(VehicleDocument::class, 'uploaded_by');
    }

    public function vehicleRequests(): HasMany
    {
        return $this->hasMany(VehicleRequest::class);
    }

    public function accountStatus(): BelongsTo
    {
        return $this->belongsTo(AccountStatus::class);
    }

    public function userStatus(): BelongsTo
    {
        return $this->belongsTo(UserStatus::class);
    }
}
