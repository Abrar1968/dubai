<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'role',
        'bio',
        'image',
        'email',
        'phone',
        'social_links',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope to filter active team members.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get social link by platform.
     */
    public function getSocialLink(string $platform): ?string
    {
        return $this->social_links[$platform] ?? null;
    }

    /**
     * Check if has any social links.
     */
    public function getHasSocialLinksAttribute(): bool
    {
        return !empty(array_filter($this->social_links ?? []));
    }
}
