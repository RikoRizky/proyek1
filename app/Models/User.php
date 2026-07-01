<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'profile_photo_path',
        'perti_id',
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

    public function isPerti(): bool
    {
        return $this->role === UserRole::Perti;
    }

    public function isUnitKerja(): bool
    {
        return $this->role === UserRole::UnitKerja;
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function prodis(): HasMany
    {
        return $this->hasMany(User::class, 'perti_id');
    }

    public function perti(): BelongsTo
    {
        return $this->belongsTo(User::class, 'perti_id');
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo_path
            ? asset('uploads/profile_photos/' . $this->profile_photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }
}
