<?php

namespace App\Models;

use App\Enums\PackageType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'short_description',
        'description',
        'image',
        'thumbnail',
        'price',
        'currency',
        'discounted_price',
        'duration_days',
        'duration_nights',
        'departure_location',
        'departure_date',
        'departure_dates',
        'return_date',
        'features',
        'inclusions',
        'exclusions',
        'itinerary',
        'hotel_details',
        'max_capacity',
        'available_slots',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => PackageType::class,
            'price' => 'decimal:2',
            'discounted_price' => 'decimal:2',
            'features' => 'array',
            'inclusions' => 'array',
            'exclusions' => 'array',
            'itinerary' => 'array',
            'hotel_details' => 'array',
            'departure_dates' => 'array',
            'departure_date' => 'date',
            'return_date' => 'date',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the gallery images for this package.
     */
    public function gallery(): HasMany
    {
        return $this->hasMany(PackageGallery::class)->orderBy('sort_order');
    }

    /**
     * Get bookings for this package.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get testimonials for this package.
     */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    /**
     * Get contact inquiries for this package.
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(ContactInquiry::class);
    }

    /**
     * Scope to filter active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter featured packages.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to filter by type.
     */
    public function scopeOfType($query, PackageType $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the effective price (discounted if available).
     */
    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->discounted_price ?? $this->price);
    }

    /**
     * Check if package has discount.
     */
    public function getHasDiscountAttribute(): bool
    {
        return $this->discounted_price !== null && $this->discounted_price < $this->price;
    }

    /**
     * Get discount percentage.
     */
    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->has_discount || $this->price == 0) {
            return 0;
        }

        return (int) round((($this->price - $this->discounted_price) / $this->price) * 100);
    }
}
