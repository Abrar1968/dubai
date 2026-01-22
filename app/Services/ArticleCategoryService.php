<?php

namespace App\Services;

use App\Models\ArticleCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ArticleCategoryService
{
    /**
     * Get all categories.
     */
    public function list(): Collection
    {
        return ArticleCategory::withCount('articles')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get a category by ID.
     */
    public function getById(int $id): ArticleCategory
    {
        return ArticleCategory::findOrFail($id);
    }

    /**
     * Get a category by slug.
     */
    public function getBySlug(string $slug): ArticleCategory
    {
        return ArticleCategory::where('slug', $slug)->firstOrFail();
    }

    /**
     * Create a new category.
     */
    public function create(array $data): ArticleCategory
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        return ArticleCategory::create($data);
    }

    /**
     * Update a category.
     */
    public function update(ArticleCategory $category, array $data): ArticleCategory
    {
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return $category->fresh();
    }

    /**
     * Delete a category.
     */
    public function delete(ArticleCategory $category): bool
    {
        // Check if category has articles
        if ($category->articles()->exists()) {
            return false;
        }

        return $category->delete();
    }

    /**
     * Get categories with published article counts.
     */
    public function getWithPublishedCounts(): Collection
    {
        return ArticleCategory::withCount(['articles' => function ($query) {
            $query->where('status', 'published');
        }])->orderBy('name')->get();
    }
}
