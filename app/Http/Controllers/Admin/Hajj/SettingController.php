<?php

namespace App\Http\Controllers\Admin\Hajj;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService,
        protected MediaService $mediaService
    ) {}

    /**
     * Display settings page.
     */
    public function index(): View
    {
        $settings = $this->settingService->getGrouped('hajj');

        return view('admin.pages.settings.index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update company settings.
     */
    public function updateCompany(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_tagline' => ['nullable', 'string', 'max:255'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_whatsapp' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'company_description' => ['nullable', 'string', 'max:1000'],
            'company_logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp,svg,heic,heif', 'max:5120'],            'banner_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp,svg,heic,heif', 'max:10240'],            'hero_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp,svg,heic,heif', 'max:10240'],
        ]);

        // Handle logo upload
        if ($request->hasFile('company_logo')) {
            $oldLogo = $this->settingService->get('company_logo', 'hajj');
            if ($oldLogo) {
                $this->mediaService->deleteImage($oldLogo);
            }
            $validated['company_logo'] = $this->mediaService->uploadImage(
                $request->file('company_logo'),
                'settings'
            );
        } else {
            unset($validated['company_logo']);
        }

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            $oldBannerImage = $this->settingService->get('banner_image', 'hajj');
            if ($oldBannerImage) {
                $this->mediaService->deleteImage($oldBannerImage);
            }
            $validated['banner_image'] = $this->mediaService->uploadImage(
                $request->file('banner_image'),
                'settings/banner',
                1920,
                200
            );
        } else {
            unset($validated['banner_image']);
        }

        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            $oldHeroImage = $this->settingService->get('hero_image', 'hajj');
            if ($oldHeroImage) {
                $this->mediaService->deleteImage($oldHeroImage);
            }
            $validated['hero_image'] = $this->mediaService->uploadImage(
                $request->file('hero_image'),
                'settings/hero',
                1920,
                1080
            );
        } else {
            unset($validated['hero_image']);
        }

        $this->settingService->setMany($validated, 'hajj');

        return redirect()
            ->route('admin.hajj.settings.index')
            ->with('success', 'Company settings updated successfully.');
    }

    /**
     * Update SEO settings.
     */
    public function updateSeo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'google_analytics' => ['nullable', 'string', 'max:50'],
            'og_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp,svg,heic,heif', 'max:5120'],
        ]);

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            $oldImage = $this->settingService->get('og_image', 'hajj');
            if ($oldImage) {
                $this->mediaService->deleteImage($oldImage);
            }
            $validated['og_image'] = $this->mediaService->uploadImage(
                $request->file('og_image'),
                'settings',
                1200,
                630
            );
        } else {
            unset($validated['og_image']);
        }

        $this->settingService->setMany($validated, 'hajj');

        return redirect()
            ->route('admin.hajj.settings.index', ['tab' => 'seo'])
            ->with('success', 'SEO settings updated successfully.');
    }

    /**
     * Update social media settings.
     */
    public function updateSocial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            'social_youtube' => ['nullable', 'url', 'max:255'],
            'contact_description' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->settingService->setMany($validated, 'hajj');

        return redirect()
            ->route('admin.hajj.settings.index', ['tab' => 'social'])
            ->with('success', 'Social media settings updated successfully.');
    }

    /**
     * Update booking settings.
     */
    public function updateBooking(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_email' => ['nullable', 'email', 'max:255'],
            'booking_phone' => ['nullable', 'string', 'max:50'],
            'terms_conditions' => ['nullable', 'string', 'max:50000'],
            'privacy_policy' => ['nullable', 'string', 'max:50000'],
            'cancellation_policy' => ['nullable', 'string', 'max:50000'],
        ]);

        $this->settingService->setMany($validated, 'hajj');

        return redirect()
            ->route('admin.hajj.settings.index', ['tab' => 'booking'])
            ->with('success', 'Booking settings updated successfully.');
    }

    /**
     * Clear settings cache.
     */
    public function clearCache(): RedirectResponse
    {
        $this->settingService->clearAllCache();

        return redirect()
            ->back()
            ->with('success', 'Settings cache cleared successfully.');
    }
}
