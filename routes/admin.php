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
        // Hajj & Umrah Section Routes
        // ==========================================
        Route::prefix('hajj')->name('hajj.')->middleware('section:hajj')->group(function () {
            // Packages
            Route::resource('packages', \App\Http\Controllers\Admin\Hajj\PackageController::class);
            Route::post('packages/{package}/toggle-active', [\App\Http\Controllers\Admin\Hajj\PackageController::class, 'toggleActive'])->name('packages.toggle-active');
            Route::post('packages/{package}/toggle-featured', [\App\Http\Controllers\Admin\Hajj\PackageController::class, 'toggleFeatured'])->name('packages.toggle-featured');

            // Bookings
            Route::resource('bookings', \App\Http\Controllers\Admin\Hajj\BookingController::class);
            Route::post('bookings/{booking}/update-status', [\App\Http\Controllers\Admin\Hajj\BookingController::class, 'updateStatus'])->name('bookings.update-status');

            // Articles
            Route::resource('articles', \App\Http\Controllers\Admin\Hajj\ArticleController::class);
            Route::post('articles/{article}/publish', [\App\Http\Controllers\Admin\Hajj\ArticleController::class, 'publish'])->name('articles.publish');
            Route::post('articles/{article}/unpublish', [\App\Http\Controllers\Admin\Hajj\ArticleController::class, 'unpublish'])->name('articles.unpublish');

            // Article Categories
            Route::resource('article-categories', \App\Http\Controllers\Admin\Hajj\ArticleCategoryController::class)->except(['show']);

            // Team Members
            Route::resource('team', \App\Http\Controllers\Admin\Hajj\TeamMemberController::class);
            Route::post('team/reorder', [\App\Http\Controllers\Admin\Hajj\TeamMemberController::class, 'reorder'])->name('team.reorder');

            // Testimonials
            Route::resource('testimonials', \App\Http\Controllers\Admin\Hajj\TestimonialController::class);
            Route::post('testimonials/{testimonial}/approve', [\App\Http\Controllers\Admin\Hajj\TestimonialController::class, 'approve'])->name('testimonials.approve');
            Route::post('testimonials/{testimonial}/reject', [\App\Http\Controllers\Admin\Hajj\TestimonialController::class, 'reject'])->name('testimonials.reject');
            Route::post('testimonials/{testimonial}/toggle-featured', [\App\Http\Controllers\Admin\Hajj\TestimonialController::class, 'toggleFeatured'])->name('testimonials.toggle-featured');

            // Contact Inquiries
            Route::resource('inquiries', \App\Http\Controllers\Admin\Hajj\ContactInquiryController::class)->only(['index', 'show', 'destroy']);
            Route::post('inquiries/{inquiry}/mark-read', [\App\Http\Controllers\Admin\Hajj\ContactInquiryController::class, 'markAsRead'])->name('inquiries.mark-read');
            Route::post('inquiries/{inquiry}/respond', [\App\Http\Controllers\Admin\Hajj\ContactInquiryController::class, 'respond'])->name('inquiries.respond');
            Route::post('inquiries/{inquiry}/close', [\App\Http\Controllers\Admin\Hajj\ContactInquiryController::class, 'close'])->name('inquiries.close');

            // FAQs
            Route::resource('faqs', \App\Http\Controllers\Admin\Hajj\FaqController::class)->except(['show']);
            Route::post('faqs/reorder', [\App\Http\Controllers\Admin\Hajj\FaqController::class, 'reorder'])->name('faqs.reorder');

            // Settings
            Route::get('settings', [\App\Http\Controllers\Admin\Hajj\SettingsController::class, 'index'])->name('settings.index');
            Route::put('settings', [\App\Http\Controllers\Admin\Hajj\SettingsController::class, 'update'])->name('settings.update');

            // Office Locations
            Route::resource('locations', \App\Http\Controllers\Admin\Hajj\OfficeLocationController::class)->except(['show']);
            Route::post('locations/reorder', [\App\Http\Controllers\Admin\Hajj\OfficeLocationController::class, 'reorder'])->name('locations.reorder');
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
        // Super Admin Only Routes
        // ==========================================
        Route::prefix('system')->name('system.')->middleware('super_admin')->group(function () {
            // Admin Management
            Route::resource('admins', \App\Http\Controllers\Admin\System\AdminController::class);
            Route::post('admins/{admin}/toggle-active', [\App\Http\Controllers\Admin\System\AdminController::class, 'toggleActive'])->name('admins.toggle-active');
            Route::put('admins/{admin}/sections', [\App\Http\Controllers\Admin\System\AdminController::class, 'updateSections'])->name('admins.update-sections');

            // Global Settings
            Route::get('settings', [\App\Http\Controllers\Admin\System\SettingsController::class, 'index'])->name('settings.index');
            Route::put('settings', [\App\Http\Controllers\Admin\System\SettingsController::class, 'update'])->name('settings.update');
        });
    });
});
