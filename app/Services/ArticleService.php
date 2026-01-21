<?php

namespace App\Services;

use App\Enums\PublishStatus;
use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ArticleService
{
    /**
     * Get all articles.
     */
    public function list(?PublishStatus $status = null): Collection
    {
        $query = Article::with(['category', 'author'])->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Get paginated articles.
     */
    public function paginate(int $perPage = 15, ?PublishStatus $status = null): LengthAwarePaginator
    {
        $query = Article::with(['category', 'author'])->orderBy('published_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get published articles (for public).
     */
    public function getPublished(int $perPage = 12): LengthAwarePaginator
    {
        return Article::with(['category', 'author'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get an article by ID.
     */
    public function getById(int $id): Article
    {
        return Article::with(['category', 'author'])->findOrFail($id);
    }

    /**
     * Get an article by slug.
     */
    public function getBySlug(string $slug): Article
    {
        return Article::with(['category', 'author'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * Create a new article.
     */
    public function create(array $data): Article
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['author_id'] = $data['author_id'] ?? auth()->id();

        if (($data['status'] ?? null) === PublishStatus::PUBLISHED && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return Article::create($data);
    }

    /**
     * Update an article.
     */
    public function update(Article $article, array $data): Article
    {
        // Only regenerate slug if title changed and slug not provided
        if (isset($data['title']) && !isset($data['slug']) && $data['title'] !== $article->title) {
            $baseSlug = Str::slug($data['title']);
            $slug = $baseSlug;
            $counter = 1;

            // Ensure unique slug (excluding current article)
            while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $data['slug'] = $slug;
        }

        // Handle publishing
        if (
            ($data['status'] ?? $article->status) === PublishStatus::PUBLISHED
            && !$article->published_at
            && empty($data['published_at'])
        ) {
            $data['published_at'] = now();
        }

        $article->update($data);

        return $article->fresh();
    }

    /**
     * Delete an article.
     */
    public function delete(Article $article): bool
    {
        return $article->delete();
    }

    /**
     * Publish an article.
     */
    public function publish(Article $article): Article
    {
        return $this->update($article, [
            'status' => PublishStatus::PUBLISHED,
            'published_at' => now(),
        ]);
    }

    /**
     * Unpublish an article (set to draft).
     */
    public function unpublish(Article $article): Article
    {
        return $this->update($article, [
            'status' => PublishStatus::DRAFT,
        ]);
    }

    /**
     * Get related articles.
     */
    public function getRelated(Article $article, int $limit = 3): Collection
    {
        return Article::published()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get article statistics.
     */
    public function getStats(): array
    {
        return [
            'total' => Article::count(),
            'published' => Article::where('status', PublishStatus::PUBLISHED)->count(),
            'draft' => Article::where('status', PublishStatus::DRAFT)->count(),
            'total_views' => Article::sum('views_count'),
        ];
    }
}
