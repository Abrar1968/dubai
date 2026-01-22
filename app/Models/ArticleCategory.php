<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get articles in this category.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    /**
     * Get published articles count.
     */
    public function getPublishedArticlesCountAttribute(): int
    {
        return $this->articles()->where('status', 'published')->count();
    }

    /**
     * Scope to order alphabetically.
     */
    public function scopeAlphabetical($query)
    {
        return $query->orderBy('name');
    }
}
