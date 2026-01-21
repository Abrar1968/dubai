<?php

use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group.
|
*/

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (login)
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
    });

    // Authenticated admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        // Logout
        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // ==========================================
        // Shared Admin Routes (accessible from any section)
        // ==========================================

        // Packages
        Route::resource('packages', \App\Http\Controllers\Admin\Hajj\PackageController::class);
        Route::patch('packages/{package}/toggle-status', [\App\Http\Controllers\Admin\Hajj\PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
        Route::patch('packages/{package}/toggle-featured', [\App\Http\Controllers\Admin\Hajj\PackageController::class, 'toggleFeatured'])->name('packages.toggle-featured');

        // Bookings
        Route::resource('bookings', \App\Http\Controllers\Admin\Hajj\BookingController::class)->except(['create', 'store', 'edit', 'update']);
        Route::patch('bookings/{booking}/update-status', [\App\Http\Controllers\Admin\Hajj\BookingController::class, 'updateStatus'])->name('bookings.update-status');
        Route::patch('bookings/{booking}/confirm', [\App\Http\Controllers\Admin\Hajj\BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::patch('bookings/{booking}/cancel', [\App\Http\Controllers\Admin\Hajj\BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::patch('bookings/{booking}/update-payment', [\App\Http\Controllers\Admin\Hajj\BookingController::class, 'updatePayment'])->name('bookings.update-payment');

        // Articles
        Route::resource('articles', \App\Http\Controllers\Admin\Hajj\ArticleController::class);
        Route::patch('articles/{article}/publish', [\App\Http\Controllers\Admin\Hajj\ArticleController::class, 'publish'])->name('articles.publish');
        Route::patch('articles/{article}/unpublish', [\App\Http\Controllers\Admin\Hajj\ArticleController::class, 'unpublish'])->name('articles.unpublish');

        // Article Categories
        Route::resource('article-categories', \App\Http\Controllers\Admin\Hajj\ArticleCategoryController::class)->except(['show']);

        // ==========================================
        // Hajj & Umrah Section Routes
        // ==========================================
        Route::prefix('hajj')->name('hajj.')->middleware('section:hajj')->group(function () {
            // Packages
            Route::resource('packages', \App\Http\Controllers\Admin\Hajj\PackageController::class);
            Route::patch('packages/{package}/toggle-status', [\App\Http\Controllers\Admin\Hajj\PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
            Route::patch('packages/{package}/toggle-featured', [\App\Http\Controllers\Admin\Hajj\PackageController::class, 'toggleFeatured'])->name('packages.toggle-featured');

            // Bookings
            Route::resource('bookings', \App\Http\Controllers\Admin\Hajj\BookingController::class)->except(['create', 'store', 'edit', 'update']);
            Route::patch('bookings/{booking}/update-status', [\App\Http\Controllers\Admin\Hajj\BookingController::class, 'updateStatus'])->name('bookings.update-status');
            Route::patch('bookings/{booking}/confirm', [\App\Http\Controllers\Admin\Hajj\BookingController::class, 'confirm'])->name('bookings.confirm');
            Route::patch('bookings/{booking}/cancel', [\App\Http\Controllers\Admin\Hajj\BookingController::class, 'cancel'])->name('bookings.cancel');
            Route::patch('bookings/{booking}/update-payment', [\App\Http\Controllers\Admin\Hajj\BookingController::class, 'updatePayment'])->name('bookings.update-payment');

            // Articles
            Route::resource('articles', \App\Http\Controllers\Admin\Hajj\ArticleController::class);
            Route::patch('articles/{article}/publish', [\App\Http\Controllers\Admin\Hajj\ArticleController::class, 'publish'])->name('articles.publish');
            Route::patch('articles/{article}/unpublish', [\App\Http\Controllers\Admin\Hajj\ArticleController::class, 'unpublish'])->name('articles.unpublish');

            // Article Categories
            Route::resource('article-categories', \App\Http\Controllers\Admin\Hajj\ArticleCategoryController::class)->except(['show']);

            // ==========================================
            // Day 3 Controllers
            // ==========================================

            // Team Members
            Route::resource('team', \App\Http\Controllers\Admin\Hajj\TeamMemberController::class);
            Route::patch('team/{team}/toggle-active', [\App\Http\Controllers\Admin\Hajj\TeamMemberController::class, 'toggleActive'])->name('team.toggle-active');
            Route::post('team/reorder', [\App\Http\Controllers\Admin\Hajj\TeamMemberController::class, 'reorder'])->name('team.reorder');

            // Testimonials
            Route::resource('testimonials', \App\Http\Controllers\Admin\Hajj\TestimonialController::class);
            Route::patch('testimonials/{testimonial}/approve', [\App\Http\Controllers\Admin\Hajj\TestimonialController::class, 'approve'])->name('testimonials.approve');
            Route::patch('testimonials/{testimonial}/reject', [\App\Http\Controllers\Admin\Hajj\TestimonialController::class, 'reject'])->name('testimonials.reject');
            Route::patch('testimonials/{testimonial}/toggle-featured', [\App\Http\Controllers\Admin\Hajj\TestimonialController::class, 'toggleFeatured'])->name('testimonials.toggle-featured');

            // Contact Inquiries - Bulk operations MUST come before resource route
            Route::post('inquiries/bulk-mark-read', [\App\Http\Controllers\Admin\Hajj\InquiryController::class, 'bulkMarkRead'])->name('inquiries.bulk-mark-read');
            Route::delete('inquiries/bulk-delete', [\App\Http\Controllers\Admin\Hajj\InquiryController::class, 'bulkDelete'])->name('inquiries.bulk-delete');
            Route::resource('inquiries', \App\Http\Controllers\Admin\Hajj\InquiryController::class)->only(['index', 'show', 'destroy']);
            Route::patch('inquiries/{inquiry}/mark-read', [\App\Http\Controllers\Admin\Hajj\InquiryController::class, 'markRead'])->name('inquiries.mark-read');
            Route::patch('inquiries/{inquiry}/mark-responded', [\App\Http\Controllers\Admin\Hajj\InquiryController::class, 'markResponded'])->name('inquiries.mark-responded');
            Route::patch('inquiries/{inquiry}/close', [\App\Http\Controllers\Admin\Hajj\InquiryController::class, 'close'])->name('inquiries.close');

            // FAQs
            Route::resource('faqs', \App\Http\Controllers\Admin\Hajj\FaqController::class)->except(['show']);
            Route::patch('faqs/{faq}/toggle-active', [\App\Http\Controllers\Admin\Hajj\FaqController::class, 'toggleActive'])->name('faqs.toggle-active');
            Route::post('faqs/reorder', [\App\Http\Controllers\Admin\Hajj\FaqController::class, 'reorder'])->name('faqs.reorder');

            // Settings
            Route::get('settings', [\App\Http\Controllers\Admin\Hajj\SettingController::class, 'index'])->name('settings.index');
            Route::put('settings', [\App\Http\Controllers\Admin\Hajj\SettingController::class, 'update'])->name('settings.update');
            Route::put('settings/company', [\App\Http\Controllers\Admin\Hajj\SettingController::class, 'updateCompany'])->name('settings.update-company');
            Route::put('settings/seo', [\App\Http\Controllers\Admin\Hajj\SettingController::class, 'updateSeo'])->name('settings.update-seo');
            Route::put('settings/social', [\App\Http\Controllers\Admin\Hajj\SettingController::class, 'updateSocial'])->name('settings.update-social');
            Route::put('settings/booking', [\App\Http\Controllers\Admin\Hajj\SettingController::class, 'updateBooking'])->name('settings.update-booking');
            Route::post('settings/clear-cache', [\App\Http\Controllers\Admin\Hajj\SettingController::class, 'clearCache'])->name('settings.clear-cache');
        });

        // ==========================================
        // Tour & Travel Section Routes (Phase 2)
        // ==========================================
        Route::prefix('tour')->name('tour.')->middleware('section:tour')->group(function () {
            // Placeholder - will be implemented in Phase 2
            Route::get('/', function () {
                return view('admin.pages.coming-soon', ['section' => 'Tour & Travel']);
            })->name('index');
        });

        // ==========================================
        // Typing Services Section Routes (Phase 3)
        // ==========================================
        Route::prefix('typing')->name('typing.')->middleware('section:typing')->group(function () {
            // Placeholder - will be implemented in Phase 3
            Route::get('/', function () {
                return view('admin.pages.coming-soon', ['section' => 'Typing Services']);
            })->name('index');
        });

        // ==========================================
        // User Management (Global - All Admins)
        // ==========================================
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['create', 'store']);
        Route::patch('users/{user}/toggle-active', [\App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('users.toggle-active');

        // ==========================================
        // Super Admin Only Routes
        // ==========================================
        Route::middleware('super_admin')->group(function () {
            // Admin Management
            Route::resource('admins', \App\Http\Controllers\Admin\AdminUserController::class);
            Route::patch('admins/{admin}/toggle-active', [\App\Http\Controllers\Admin\AdminUserController::class, 'toggleActive'])->name('admins.toggle-active');
        });
    });
});
