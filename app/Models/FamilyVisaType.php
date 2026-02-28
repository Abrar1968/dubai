<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FamilyVisaType extends Model
{
    use HasFactory;

    protected $fillable = [
        'emirate_id',
        'name',
        'slug',
        'short_description',
        'long_description',
        'requirements',
        'documents',
        'process_steps',
        'processing_time',
        'price_range',
        'cta_text',
        'cta_link',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requirements' => 'array',
            'documents' => 'array',
            'process_steps' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (FamilyVisaType $type) {
            if (empty($type->slug)) {
                $type->slug = Str::slug($type->name);
            }
        });
    }

    /**
     * Get the emirate this visa type belongs to.
     */
    public function emirate(): BelongsTo
    {
        return $this->belongsTo(FamilyVisaEmirate::class, 'emirate_id');
    }

    /**
     * Scope to filter active visa types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get URL for this visa type page.
     */
    public function getUrlAttribute(): string
    {
        return '/typing/services/family-visa/' . $this->emirate->slug . '/' . $this->slug;
    }
}
