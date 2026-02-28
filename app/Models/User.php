<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property-read Carbon|null $date_of_birth
 * @property-read Carbon|null $passport_expiry
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'date_of_birth',
        'passport_number',
        'passport_expiry',
        'nationality',
        'avatar',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'two_factor_confirmed_at' => 'datetime',
            'role' => UserRole::class,
            'date_of_birth' => 'datetime',
            'passport_expiry' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    // ==========================================
    // Role Helper Methods
    // ==========================================

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SUPER_ADMIN;
    }

    /**
     * Check if user is an admin (not super admin).
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    /**
     * Check if user is a regular user.
     */
    public function isUser(): bool
    {
        return $this->role === UserRole::USER;
    }

    /**
     * Check if user has admin-level access (super admin or admin).
     */
    public function isAdminLevel(): bool
    {
        return $this->role?->isAdminLevel() ?? false;
    }

    /**
     * Check if user has access to a specific section.
     */
    public function hasSection(string $section): bool
    {
        // Super admins have access to all sections
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->assignedSections()->where('section', $section)->exists();
    }

    /**
     * Get all section names user has access to.
     */
    public function getSectionNames(): array
    {
        if ($this->isSuperAdmin()) {
            return ['hajj', 'tour', 'typing'];
        }

        return $this->assignedSections()->pluck('section')->toArray();
    }

    // ==========================================
    // Relationships
    // ==========================================

    /**
     * Get the admin sections assigned to this user.
     */
    public function assignedSections(): HasMany
    {
        return $this->hasMany(AdminSection::class);
    }

    /**
     * Get bookings made by this user.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get articles authored by this user.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    /**
     * Get booking status logs where this user made changes.
     */
    public function bookingStatusChanges(): HasMany
    {
        return $this->hasMany(BookingStatusLog::class, 'changed_by');
    }

    // ==========================================
    // Scopes
    // ==========================================

    /**
     * Scope to filter active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by role.
     */
    public function scopeRole($query, UserRole $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope to filter admin-level users.
     */
    public function scopeAdmins($query)
    {
        return $query->whereIn('role', [UserRole::SUPER_ADMIN, UserRole::ADMIN]);
    }

    /**
     * Scope to filter regular users only.
     */
    public function scopeCustomers($query)
    {
        return $query->where('role', UserRole::USER);
    }

    // ==========================================
    // Accessors
    // ==========================================

    /**
     * Get user's initials for avatar fallback.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return $initials;
    }

    /**
     * Get age of user.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return $this->date_of_birth->age;
    }
}
