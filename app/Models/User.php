<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'status',
        'two_factor_enabled',
        'two_factor_secret',
        'last_login_at',
        'last_login_ip',
        'assigned_categories',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'last_login_at' => 'datetime',
            'assigned_categories' => 'array',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isGeneralAdmin(): bool
    {
        return $this->role === 'general_admin';
    }

    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['super_admin', 'general_admin', 'moderator']);
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Check if user has specific permission.
     * Super Admin always has full bypass.
     * General Admin and Moderator require explicit permission.
     */
    public function hasPermission(string $permissionKey): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->status !== 'active') {
            return false;
        }

        // Direct user permission override check
        $override = UserPermissionOverride::where('user_id', $this->id)
            ->whereHas('permission', function ($q) use ($permissionKey) {
                $q->where('key', $permissionKey);
            })->first();

        if ($override !== null) {
            return (bool)$override->is_granted;
        }

        // Role-based permissions check
        return $this->roles()->whereHas('permissions', function ($q) use ($permissionKey) {
            $q->where('key', $permissionKey);
        })->exists();
    }

    /**
     * Category-scoped access check for staff.
     */
    public function canAccessCategory(?int $categoryId): bool
    {
        if ($this->isSuperAdmin() || empty($this->assigned_categories) || !$categoryId) {
            return true;
        }

        return in_array($categoryId, (array)$this->assigned_categories);
    }

    // Relationships
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
    }

    public function permissionOverrides(): HasMany
    {
        return $this->hasMany(UserPermissionOverride::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(UserSubscription::class)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latestOfMany();
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function downloadLogs(): HasMany
    {
        return $this->hasMany(ProductDownloadLog::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(ProductLicense::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }
}
