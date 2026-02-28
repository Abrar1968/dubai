<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'name',
        'address',
        'phone',
        'email',
        'map_lat',
        'map_lng',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'map_lat' => 'decimal:8',
            'map_lng' => 'decimal:8',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope to filter active locations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by section.
     */
    public function scopeSection($query, string $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Check if has map coordinates.
     */
    public function getHasMapCoordinatesAttribute(): bool
    {
        return $this->map_lat !== null && $this->map_lng !== null;
    }

    /**
     * Get formatted address for display.
     */
    public function getFormattedAddressAttribute(): string
    {
        return nl2br(e($this->address));
    }

    /**
     * Get Google Maps URL.
     */
    public function getGoogleMapsUrlAttribute(): ?string
    {
        if (!$this->has_map_coordinates) {
            return null;
        }

        return "https://www.google.com/maps?q={$this->map_lat},{$this->map_lng}";
    }
}
