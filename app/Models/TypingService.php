<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TypingService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'long_description',
        'icon',
        'image',
        'sub_services',
        'cta_text',
        'cta_link',
        'sort_order',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['image_url', 'url'];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'sub_services' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        // Auto-generate slug from title if not provided
        static::creating(function (TypingService $service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });

        static::updating(function (TypingService $service) {
            if ($service->isDirty('title') && !$service->isDirty('slug')) {
                $service->slug = Str::slug($service->title);
            }
        });
    }

    /**
     * Scope a query to only include active services.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured services.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to order by sort_order and title.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Get the full URL for the service image.
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        return null;
    }

    /**
     * Get the public URL for this service.
     */
    public function getUrlAttribute(): string
    {
        return '/typing/services/' . $this->slug;
    }
}
