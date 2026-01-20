<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Enums\PackageType;
use App\Enums\PublishStatus;
use App\Services\PackageService;
use App\Services\ArticleService;
use App\Services\TeamMemberService;
use App\Services\TestimonialService;
use App\Services\FaqService;
use App\Services\ContactInquiryService;
use App\Models\SiteSetting;
use App\Models\OfficeLocation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HajjController extends Controller
{
    public function __construct(
        protected PackageService $packageService,
        protected ArticleService $articleService,
        protected TeamMemberService $teamService,
        protected TestimonialService $testimonialService,
        protected FaqService $faqService,
        protected ContactInquiryService $inquiryService
    ) {}

    /**
     * Hajj & Umrah Home Page
     */
    public function home(): Response
    {
        // Get featured packages (limit 4 for homepage)
        $packages = $this->packageService->getFeatured(4)->map(fn($pkg) => [
            'id' => $pkg->id,
            'title' => $pkg->title,
            'slug' => $pkg->slug,
            'price' => $pkg->price,
            'currency' => $pkg->currency,
            'duration_days' => $pkg->duration_days,
            'duration_nights' => $pkg->duration_nights,
            'image' => $pkg->thumbnail ? asset('storage/' . $pkg->thumbnail) : ($pkg->image ? asset('storage/' . $pkg->image) : '/assets/img/hajj/hajjbg.jpg'),
            'features' => $pkg->features ?? [],
            'type' => $pkg->type->value,
        ]);

        // Get recent published articles (limit 3)
        $articles = $this->articleService->list(PublishStatus::PUBLISHED)
            ->take(3)
            ->map(fn($article) => [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'category' => $article->category?->name ?? 'Travel Guide',
                'image' => $article->featured_image ? asset('storage/' . $article->featured_image) : '/assets/img/hajj/hajjbg.jpg',
            ]);

        // Get approved testimonials (limit 3)
        $testimonials = $this->testimonialService->getApproved()
            ->take(3)
            ->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'location' => $t->location,
                'content' => $t->content,
                'rating' => $t->rating,
                'avatar' => $t->avatar ? asset('storage/' . $t->avatar) : null,
            ]);

        // Get site settings
        $settings = $this->getSettings('hajj');

        return Inertia::render('hajj&umrah/hajjhome', [
            'packages' => $packages,
            'articles' => $articles,
            'testimonials' => $testimonials,
            'settings' => $settings,
        ]);
    }

    /**
     * Hajj Packages List
     */
    public function hajjPackages(): Response
    {
        $packages = $this->packageService->list(PackageType::HAJJ, true)
            ->map(fn($pkg) => [
                'id' => $pkg->id,
                'title' => $pkg->title,
                'slug' => $pkg->slug,
                'price' => $pkg->price,
                'currency' => $pkg->currency,
                'duration_days' => $pkg->duration_days,
                'duration_nights' => $pkg->duration_nights,
                'image' => $pkg->thumbnail ? asset('storage/' . $pkg->thumbnail) : ($pkg->image ? asset('storage/' . $pkg->image) : '/assets/img/hajj/hajjbg.jpg'),
                'features' => $pkg->features ?? [],
                'departure_dates' => $pkg->departure_dates ?? [],
            ]);

        $settings = $this->getSettings('hajj');

        return Inertia::render('hajj&umrah/hajjpackage', [
            'packages' => $packages,
            'settings' => $settings,
            'headerBg' => $settings['packages_header_image'] ?? '/images/packages/header.jpg',
        ]);
    }

    /**
     * Umrah Packages List
     */
    public function umrahPackages(): Response
    {
        $packages = $this->packageService->list(PackageType::UMRAH, true)
            ->map(fn($pkg) => [
                'id' => $pkg->id,
                'title' => $pkg->title,
                'slug' => $pkg->slug,
                'price' => $pkg->price,
                'currency' => $pkg->currency,
                'duration_days' => $pkg->duration_days,
                'duration_nights' => $pkg->duration_nights,
                'image' => $pkg->thumbnail ? asset('storage/' . $pkg->thumbnail) : ($pkg->image ? asset('storage/' . $pkg->image) : '/images/packages/p1.jpg'),
                'features' => $pkg->features ?? [],
                'departure_dates' => $pkg->departure_dates ?? [],
            ]);

        $settings = $this->getSettings('hajj');

        return Inertia::render('hajj&umrah/umrahpackage', [
            'packages' => $packages,
            'settings' => $settings,
            'headerBg' => $settings['packages_header_image'] ?? '/images/packages/header.jpg',
        ]);
    }

    /**
     * Package Detail Page
     */
    public function packageShow(string $slug): Response
    {
        $package = $this->packageService->getBySlug($slug);

        if (!$package || !$package->is_active) {
            abort(404);
        }

        // Get related packages
        $relatedPackages = $this->packageService->list($package->type, true)
            ->where('id', '!=', $package->id)
            ->take(3)
            ->map(fn($pkg) => [
                'id' => $pkg->id,
                'title' => $pkg->title,
                'slug' => $pkg->slug,
                'price' => $pkg->price,
                'duration_days' => $pkg->duration_days,
                'image' => $pkg->thumbnail ? asset('storage/' . $pkg->thumbnail) : ($pkg->image ? asset('storage/' . $pkg->image) : null),
            ])
            ->values();

        return Inertia::render('hajj&umrah/package_detail', [
            'package' => [
                'id' => $package->id,
                'title' => $package->title,
                'slug' => $package->slug,
                'type' => $package->type->value,
                'price' => $package->price,
                'currency' => $package->currency,
                'duration_days' => $package->duration_days,
                'duration_nights' => $package->duration_nights,
                'image' => $package->thumbnail ? asset('storage/' . $package->thumbnail) : ($package->image ? asset('storage/' . $package->image) : '/assets/img/hajj/hajjbg.jpg'),
                'thumbnail' => $package->thumbnail ? asset('storage/' . $package->thumbnail) : ($package->image ? asset('storage/' . $package->image) : '/assets/img/hajj/hajjbg.jpg'),
                'description' => $package->description,
                'features' => $package->features ?? [],
                'inclusions' => $package->inclusions ?? [],
                'exclusions' => $package->exclusions ?? [],
                'itinerary' => $package->itinerary ?? [],
                'hotel_details' => $package->hotel_details ?? [],
                'departure_dates' => $package->departure_dates ?? [],
                'max_capacity' => $package->max_capacity,
                'gallery' => $package->gallery->map(fn($img) => [
                    'url' => asset('storage/' . $img->image_path),
                    'alt' => $img->alt_text ?? $package->title,
                ])->toArray(),
            ],
            'relatedPackages' => $relatedPackages,
        ]);
    }

    /**
     * Articles/Blog List
     */
    public function articles(): Response
    {
        $articles = $this->articleService->list(PublishStatus::PUBLISHED)
            ->map(fn($article) => [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'category' => $article->category?->name ?? 'Travel Guide',
                'image' => $article->featured_image ? asset('storage/' . $article->featured_image) : '/assets/img/hajj/hajjbg.jpg',
                'published_at' => $article->published_at?->format('M d, Y'),
            ]);

        return Inertia::render('hajj&umrah/articles', [
            'articles' => $articles,
        ]);
    }

    /**
     * Single Article Detail
     */
    public function articleShow(string $slug): Response
    {
        $article = $this->articleService->getBySlug($slug);

        if (!$article || $article->status !== PublishStatus::PUBLISHED) {
            abort(404);
        }

        // Increment view count
        $article->increment('views_count');

        // Get related articles
        $relatedArticles = $this->articleService->getRelated($article, 3)
            ->map(fn($a) => [
                'id' => $a->id,
                'title' => $a->title,
                'slug' => $a->slug,
                'image' => $a->featured_image ? asset('storage/' . $a->featured_image) : null,
            ]);

        return Inertia::render('hajj&umrah/article_detail', [
            'article' => [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'content' => $article->content,
                'category' => $article->category?->name ?? 'Travel Guide',
                'image' => $article->featured_image ? asset('storage/' . $article->featured_image) : null,
                'author' => $article->author?->name ?? 'Admin',
                'published_at' => $article->published_at?->format('M d, Y'),
                'views_count' => $article->views_count,
                'tags' => $article->tags ?? [],
            ],
            'relatedArticles' => $relatedArticles,
        ]);
    }

    /**
     * Team Page
     */
    public function team(): Response
    {
        $teamMembers = $this->teamService->list(true)
            ->map(fn($member) => [
                'id' => $member->id,
                'name' => $member->name,
                'role' => $member->role,
                'bio' => $member->bio,
                'image' => $member->image ? asset('storage/' . $member->image) : '/assets/img/team/1.jpg',
                'social_links' => $member->social_links ?? [],
            ]);

        // Get FAQs for team page
        $faqs = $this->faqService->list('hajj')
            ->where('is_active', true)
            ->take(5)
            ->map(fn($faq) => [
                'id' => $faq->id,
                'question' => $faq->question,
                'answer' => $faq->answer,
            ])
            ->values();

        return Inertia::render('hajj&umrah/team', [
            'teamMembers' => $teamMembers,
            'faqs' => $faqs,
        ]);
    }

    /**
     * Contact Us Page
     */
    public function contact(): Response
    {
        $settings = $this->getSettings('hajj');

        // Get office locations
        $offices = OfficeLocation::where('section', 'hajj')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($office) => [
                'id' => $office->id,
                'name' => $office->name,
                'address' => $office->address,
                'phone' => $office->phone,
                'email' => $office->email,
            ]);

        return Inertia::render('hajj&umrah/contactus', [
            'offices' => $offices,
            'settings' => $settings,
        ]);
    }

    /**
     * Handle contact form submission
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'package_id' => 'nullable|exists:packages,id',
        ]);

        $validated['section'] = 'hajj';

        $this->inquiryService->create($validated);

        return back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }

    /**
     * Get site settings for a section
     */
    protected function getSettings(string $section): array
    {
        return SiteSetting::where('section', $section)
            ->pluck('value', 'key')
            ->toArray();
    }
}
