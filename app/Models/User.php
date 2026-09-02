<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'birth_date',
        'address',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function memberCards(): HasMany
    {
        return $this->hasMany(MemberCard::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function trainerProfile(): BelongsTo
    {
        return $this->belongsTo(Trainer::class, 'id', 'user_id');
    }

    public function trainerBookings(): HasMany
    {
        return $this->hasMany(TrainerBooking::class);
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('unlocked_at')
            ->withTimestamps();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function activeMemberCard(): ?MemberCard
    {
        return $this->memberCards()
            ->where('status', 'active')
            ->whereDate('end_date', '>=', today())
            ->orderByDesc('end_date')
            ->first();
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isTrainer(): bool
    {
        return $this->role?->name === 'trainer';
    }

    public function isMember(): bool
    {
        return $this->role?->name === 'member';
    }

    public function homeRoute(): string
    {
        return match (true) {
            $this->isAdmin() => 'admin.dashboard',
            $this->isTrainer() => 'trainer.dashboard',
            default => 'user.dashboard',
        };
    }
}
