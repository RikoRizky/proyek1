<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isAsesor(): bool
    {
        return $this->role === UserRole::Asesor;
    }

    public function isUnitKerja(): bool
    {
        return $this->role === UserRole::UnitKerja;
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'asesor_id');
    }
}
