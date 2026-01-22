<?php

namespace App\Http\Controllers\Admin\Hajj;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleCategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $categories = ArticleCategory::withCount('articles')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.pages.article-categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('admin.pages.article-categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:article_categories',
            'slug' => 'nullable|string|max:255|unique:article_categories',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        ArticleCategory::create($validated);

        return redirect()
            ->route('admin.article-categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(ArticleCategory $articleCategory): View
    {
        return view('admin.pages.article-categories.edit', [
            'category' => $articleCategory,
        ]);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, ArticleCategory $articleCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:article_categories,name,' . $articleCategory->id,
            'slug' => 'nullable|string|max:255|unique:article_categories,slug,' . $articleCategory->id,
            'description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        $articleCategory->update($validated);

        return redirect()
            ->route('admin.article-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(ArticleCategory $articleCategory): RedirectResponse
    {
        // Check if category has articles
        if ($articleCategory->articles()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing articles. Please reassign or delete the articles first.');
        }

        $articleCategory->delete();

        return redirect()
            ->route('admin.article-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
