<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'active_package',
        'package_valid_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'            => 'hashed',
            'role'                => UserRole::class,
            'package_valid_until' => 'datetime',
        ];
    }

    // ─── Role helpers ─────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isPerti(): bool
    {
        return $this->role === UserRole::Perti;
    }

    public function isProdi(): bool
    {
        return $this->role === UserRole::Prodi;
    }

    // ─── Relations ────────────────────────────────────────────────────

    /**
     * Profil Perguruan Tinggi untuk akun dengan role=perti.
     */
    public function pertiProfile(): HasOne
    {
        return $this->hasOne(Perti::class, 'user_id');
    }

    /**
     * Profil Program Studi untuk akun dengan role=prodi.
     */
    public function prodiProfile(): HasOne
    {
        return $this->hasOne(Prodi::class, 'user_id');
    }

    /**
     * Semua submission yang diunggah oleh user ini.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    // ─── Accessories ─────────────────────────────────────────────────

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo_path
            ? asset('uploads/profile_photos/' . $this->profile_photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }
}
