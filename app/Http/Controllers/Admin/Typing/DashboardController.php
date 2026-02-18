<?php

namespace App\Http\Controllers\Admin\Typing;

use App\Http\Controllers\Controller;
use App\Services\ContactInquiryService;
use App\Services\TypingServiceService;
use App\Services\FamilyVisaService;
use App\Services\OfficeLocationService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected TypingServiceService $typingServiceService,
        protected ContactInquiryService $inquiryService,
        protected FamilyVisaService $familyVisaService,
        protected OfficeLocationService $officeLocationService
    ) {}

    /**
     * Display the typing section admin dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get typing-specific statistics
        $stats = [];

        // Typing Services stats
        $serviceStats = $this->typingServiceService->getStats();
        $stats['services'] = [
            'total' => $serviceStats['total'],
            'active' => $serviceStats['active'],
            'featured' => $serviceStats['featured'],
        ];

        // Family Visa stats
        $familyVisaStats = $this->familyVisaService->getStats();
        $stats['family_visa'] = [
            'total_emirates' => $familyVisaStats['total_emirates'],
            'total_visa_types' => $familyVisaStats['total_visa_types'],
            'active_emirates' => $familyVisaStats['active_emirates'],
            'active_visa_types' => $familyVisaStats['active_visa_types'],
        ];

        // Inquiries stats (typing section only)
        $inquiryStats = $this->inquiryService->getStatsBySection('typing');
        $stats['inquiries'] = [
            'total' => $inquiryStats['total'],
            'new' => $inquiryStats['new'],
            'responded' => $inquiryStats['responded'],
        ];

        // Office locations stats
        $officeStats = $this->officeLocationService->getStats();
        $stats['offices'] = [
            'total' => $officeStats['total'],
            'global' => $officeStats['global'],
            'typing_specific' => $officeStats['typing'] ?? 0,
        ];

        // Get recent data for typing section
        $recentInquiries = $this->inquiryService->getRecentBySection('typing', 5);

        // Get featured services for quick access
        $featuredServices = $this->typingServiceService->getFeatured(6);

        return view('admin.pages.typing.dashboard', [
            'stats' => $stats,
            'recentInquiries' => $recentInquiries,
            'featuredServices' => $featuredServices,
            'user' => $user,
        ]);
    }
}
