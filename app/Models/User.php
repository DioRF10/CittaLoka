<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone_number',
        'country_code',
        'tanggal_lahir',
        'jenis_kelamin',
        'locale',
        'avatar',
        'google_id',
        'email_verified_at',
        'soul_type_id',
        'preferred_currency',
        'terms_accepted_at',
        'last_login_at',
        'onboarding_completed_at',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'terms_accepted_at' => 'datetime',
            'last_login_at' => 'datetime',
            'onboarding_completed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public function hasCompletedOnboarding(): bool
    {
        return !is_null($this->onboarding_completed_at);
    }

    public function isHost(): bool
    {
        return $this->role === 'host';
    }

    public function isTraveler(): bool
    {
        return $this->role === 'user';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    public function avatarUrl(): string
    {
        if ($this->avatar) {
            if (str_starts_with($this->avatar, 'http')) {
                return $this->avatar;
            }
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2D5240&color=fff';
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function host()
    {
        return $this->hasOne(Host::class);
    }

    public function soulType()
    {
        return $this->belongsTo(SoulType::class, 'soul_type_id');
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedExperiences()
    {
        return $this->belongsToMany(Experience::class, 'wishlists', 'user_id', 'experience_id');
    }

    public function followedHosts()
    {
        return $this->belongsToMany(Host::class, 'host_follows', 'user_id', 'host_id');
    }

    public function hasWishlisted(int $experienceId): bool
    {
        return $this->wishlists()->where('experience_id', $experienceId)->exists();
    }

    public function isFollowing(int $hostId): bool
    {
        return $this->followedHosts()->where('host.id', $hostId)->exists();
    }

}