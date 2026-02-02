<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Services\FamilyVisaService;
use App\Services\OfficeLocationService;
use App\Services\SettingService;
use App\Services\TypingServiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TypingController extends Controller
{
    public function __construct(
        protected TypingServiceService $typingServiceService,
        protected SettingService $settingService,
        protected OfficeLocationService $officeLocationService,
        protected FamilyVisaService $familyVisaService
    ) {}

    /**
     * Display the typing services home page.
     */
    public function home(): Response
    {
        // Use methods that INCLUDE family visa for public display
        $services = $this->typingServiceService->getActiveWithFamilyVisa();
        $featuredServices = $this->typingServiceService->getFeaturedWithFamilyVisa(8);
        $settings = $this->settingService->getGrouped('typing');
        $offices = $this->officeLocationService->getForHomePage('typing');

        return Inertia::render('typing/typinghome', [
            'services' => $services,
            'featuredServices' => $featuredServices,
            'settings' => $settings,
            'offices' => $offices,
        ]);
    }

    /**
     * Display a specific typing service detail page.
     * FamilyVisaProcess has its own special Vue page due to complex emirate selection.
     * All other services use the generic ServiceDetail page with database content.
     */
    public function service(string $slug): Response
    {
        // Get settings for all pages
        $settings = $this->settingService->getGrouped('typing');

        // Family Visa Process has a special Vue page with emirate selection
        if ($slug === 'family-visa-process') {
            $emirates = $this->familyVisaService->listEmirates()
                ->map(function ($emirate) {
                    return [
                        'id' => $emirate->id,
                        'name' => $emirate->name,
                        'slug' => $emirate->slug,
                        'description' => $emirate->description,
                        'intro_text' => $emirate->intro_text,
                        'visa_types' => $emirate->activeVisaTypes->map(function ($type) {
                            return [
                                'id' => $type->id,
                                'name' => $type->name,
                                'slug' => $type->slug,
                                'short_description' => $type->short_description,
                            ];
                        })->values(),
                    ];
                })->values();

            return Inertia::render('typing/services/FamilyVisaProcess', [
                'settings' => $settings,
                'emirates' => $emirates,
            ]);
        }

        // For all other services, use the generic ServiceDetail page
        try {
            $service = $this->typingServiceService->getBySlug($slug);
        } catch (\Exception $e) {
            // Service not found, redirect to home
            return Inertia::render('typing/typinghome', [
                'services' => $this->typingServiceService->getActive(),
                'featuredServices' => $this->typingServiceService->getFeatured(6),
                'settings' => $settings,
                'offices' => $this->officeLocationService->getForHomePage('typing'),
            ]);
        }

        // Get other services for sidebar (excluding current)
        $otherServices = $this->typingServiceService->getActive()
            ->where('id', '!=', $service->id)
            ->take(6);

        return Inertia::render('typing/services/ServiceDetail', [
            'service' => $service,
            'otherServices' => $otherServices,
            'settings' => $settings,
        ]);
    }

    /**
     * Display the family visa type detail page.
     */
    public function familyVisaType(string $emirateSlug, string $typeSlug): Response
    {
        $settings = $this->settingService->getGrouped('typing');

        // This will throw 404 if not found (uses firstOrFail)
        $visaType = $this->familyVisaService->getVisaTypeBySlug($emirateSlug, $typeSlug);

        // Get other visa types from the same emirate
        $otherTypes = $visaType->emirate->activeVisaTypes
            ->where('id', '!=', $visaType->id)
            ->map(function ($type) {
                return [
                    'id' => $type->id,
                    'name' => $type->name,
                    'slug' => $type->slug,
                    'short_description' => $type->short_description,
                ];
            })->values();

        return Inertia::render('typing/services/FamilyVisaTypeDetail', [
            'settings' => $settings,
            'visaType' => [
                'id' => $visaType->id,
                'name' => $visaType->name,
                'slug' => $visaType->slug,
                'short_description' => $visaType->short_description,
                'long_description' => $visaType->long_description,
                'requirements' => $visaType->requirements ?? [],
                'documents' => $visaType->documents ?? [],
                'process_steps' => $visaType->process_steps ?? [],
                'processing_time' => $visaType->processing_time,
                'price_range' => $visaType->price_range,
                'cta_text' => $visaType->cta_text ?? 'Get Started',
                'cta_link' => $visaType->cta_link ?? '/typing/contact',
            ],
            'emirate' => [
                'id' => $visaType->emirate->id,
                'name' => $visaType->emirate->name,
                'slug' => $visaType->emirate->slug,
            ],
            'otherTypes' => $otherTypes,
        ]);
    }

    /**
     * Display all typing services list page.
     * Uses the home page which already shows all services.
     */
    public function services(): Response
    {
        $services = $this->typingServiceService->getActive();
        $settings = $this->settingService->getGrouped('typing');

        return Inertia::render('typing/typinghome', [
            'services' => $services,
            'featuredServices' => $this->typingServiceService->getFeatured(6),
            'settings' => $settings,
        ]);
    }

    /**
     * Display the contact/inquiry page for typing services.
     */
    public function contact(): Response
    {
        $settings = $this->settingService->getGrouped('typing');
        $services = $this->typingServiceService->getActive();
        $offices = $this->officeLocationService->getForHomePage('typing');

        return Inertia::render('typing/contact', [
            'settings' => $settings,
            'services' => $services,
            'offices' => $offices,
        ]);
    }

    /**
     * Store a contact inquiry from typing section.
     */
    public function storeContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'service' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactInquiry::create([
            'section' => 'typing',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'service_interested' => $validated['service'] ?? null,
            'status' => 'new',
        ]);

        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
