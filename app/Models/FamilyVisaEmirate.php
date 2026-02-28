<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class FamilyVisaEmirate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'intro_text',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (FamilyVisaEmirate $emirate) {
            if (empty($emirate->slug)) {
                $emirate->slug = Str::slug($emirate->name);
            }
        });
    }

    /**
     * Get visa types for this emirate.
     */
    public function visaTypes(): HasMany
    {
        return $this->hasMany(FamilyVisaType::class, 'emirate_id');
    }

    /**
     * Get active visa types.
     */
    public function activeVisaTypes(): HasMany
    {
        return $this->visaTypes()->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Scope to filter active emirates.
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
     * Get URL for this emirate's family visa page.
     */
    public function getUrlAttribute(): string
    {
        return '/typing/services/family-visa/' . $this->slug;
    }
}
