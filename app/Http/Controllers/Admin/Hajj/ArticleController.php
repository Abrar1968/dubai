<?php

namespace App\Http\Controllers\Admin\Hajj;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Services\ArticleService;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct(
        protected ArticleService $articleService,
        protected MediaService $mediaService
    ) {}

    /**
     * Display a listing of articles.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status') ? PublishStatus::tryFrom($request->get('status')) : null;
        $articles = $this->articleService->paginate(
            perPage: $request->get('per_page', 15),
            status: $status
        );

        return view('admin.pages.articles.index', [
            'articles' => $articles,
            'statuses' => PublishStatus::cases(),
            'currentStatus' => $status,
        ]);
    }

    /**
     * Show the form for creating a new article.
     */
    public function create(): View
    {
        $categories = ArticleCategory::orderBy('name')->get();

        return view('admin.pages.articles.create', [
            'categories' => $categories,
            'statuses' => PublishStatus::cases(),
        ]);
    }

    /**
     * Store a newly created article.
     */
    public function store(ArticleRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->mediaService->uploadImage(
                $request->file('featured_image'),
                'articles',
                1200,
                630
            );
        }

        // Set author
        $data['author_id'] = auth()->id();

        $this->articleService->create($data);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article created successfully.');
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): View
    {
        $article->load(['category', 'author']);

        return view('admin.pages.articles.show', [
            'article' => $article,
        ]);
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article): View
    {
        $categories = ArticleCategory::orderBy('name')->get();

        return view('admin.pages.articles.edit', [
            'article' => $article,
            'categories' => $categories,
            'statuses' => PublishStatus::cases(),
        ]);
    }

    /**
     * Update the specified article.
     */
    public function update(ArticleRequest $request, Article $article): RedirectResponse
    {
        $data = $request->validated();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($article->featured_image) {
                $this->mediaService->deleteImage($article->featured_image);
            }

            $data['featured_image'] = $this->mediaService->uploadImage(
                $request->file('featured_image'),
                'articles',
                1200,
                630
            );
        }

        $this->articleService->update($article, $data);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article updated successfully.');
    }

    /**
     * Remove the specified article.
     */
    public function destroy(Article $article): RedirectResponse
    {
        // Delete featured image
        if ($article->featured_image) {
            $this->mediaService->deleteImage($article->featured_image);
        }

        $this->articleService->delete($article);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article deleted successfully.');
    }

    /**
     * Publish an article.
     */
    public function publish(Article $article): RedirectResponse
    {
        $this->articleService->publish($article);

        return back()->with('success', 'Article published successfully.');
    }

    /**
     * Unpublish an article.
     */
    public function unpublish(Article $article): RedirectResponse
    {
        $this->articleService->unpublish($article);

        return back()->with('success', 'Article unpublished successfully.');
    }
}
