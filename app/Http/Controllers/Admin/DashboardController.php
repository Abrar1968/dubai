<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use App\Services\ContactInquiryService;
use App\Services\PackageService;
use App\Services\ArticleService;
use App\Services\TestimonialService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected PackageService $packageService,
        protected BookingService $bookingService,
        protected ArticleService $articleService,
        protected ContactInquiryService $inquiryService,
        protected TestimonialService $testimonialService
    ) {}

    /**
     * Display the admin dashboard (now hajj-specific).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get hajj-specific statistics
        $stats = [];

        // Package stats (if user has hajj access or is super admin)
        if ($user->isSuperAdmin() || $user->hasSection('hajj')) {
            $packageStats = $this->packageService->getStats();
            $stats['packages'] = [
                'total' => $packageStats['total'],
                'active' => $packageStats['active'],
                'featured' => $packageStats['featured'],
            ];

            $bookingStats = $this->bookingService->getStats();
            $stats['bookings'] = [
                'total' => $bookingStats['total'],
                'pending' => $bookingStats['pending'],
                'confirmed' => $bookingStats['confirmed'],
                'total_revenue' => $bookingStats['total_revenue'],
            ];

            $articleStats = $this->articleService->getStats();
            $stats['articles'] = [
                'total' => $articleStats['total'],
                'published' => $articleStats['published'],
                'total_views' => $articleStats['total_views'],
            ];

            $inquiryStats = $this->inquiryService->getStatsBySection('hajj');
            $stats['inquiries'] = [
                'total' => $inquiryStats['total'],
                'new' => $inquiryStats['new'],
                'responded' => $inquiryStats['responded'],
            ];

            $testimonialStats = $this->testimonialService->getStats();
            $stats['testimonials'] = [
                'total' => $testimonialStats['total'],
                'pending' => $testimonialStats['pending'],
                'average_rating' => $testimonialStats['average_rating'],
            ];
        }

        // Get recent data (hajj-specific)
        $recentBookings = $this->bookingService->getRecent(5);
        $recentInquiries = $this->inquiryService->getRecentBySection('hajj', 5);

        return view('admin.pages.hajj.dashboard', [
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'recentInquiries' => $recentInquiries,
            'user' => $user,
        ]);
    }
}
