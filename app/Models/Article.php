<?php

namespace App\Models;

use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category_id',
        'author_id',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views_count',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => PublishStatus::class,
            'views_count' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Get the category of this article.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    /**
     * Get the author of this article.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope to filter published articles.
     */
    public function scopePublished($query)
    {
        return $query->where('status', PublishStatus::PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to filter draft articles.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', PublishStatus::DRAFT);
    }

    /**
     * Scope to order by latest.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Scope to filter by category.
     */
    public function scopeInCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Increment view count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Get reading time estimate in minutes.
     */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content ?? ''));
        $wordsPerMinute = 200;

        return max(1, (int) ceil($wordCount / $wordsPerMinute));
    }

    /**
     * Check if article is published.
     */
    public function getIsPublishedAttribute(): bool
    {
        return $this->status === PublishStatus::PUBLISHED
            && $this->published_at !== null
            && $this->published_at->lte(now());
    }
}
