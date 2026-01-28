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

        // Dashboard - redirect to appropriate section dashboard
        Route::get('/', function () {
            $user = auth()->user();

            if ($user->isSuperAdmin()) {
                // Super admin can access all sections, default to hajj
                return redirect()->route('admin.hajj.dashboard');
            } elseif ($user->hasSection('hajj')) {
                return redirect()->route('admin.hajj.dashboard');
            } elseif ($user->hasSection('typing')) {
                return redirect()->route('admin.typing.dashboard');
            } elseif ($user->hasSection('tour')) {
                return redirect()->route('admin.tour.dashboard'); // Future
            } else {
                // Fallback to hajj dashboard
                return redirect()->route('admin.hajj.dashboard');
            }
        })->name('dashboard');

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
            // Dashboard
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

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
        // Typing Services Section Routes
        // ==========================================
        Route::prefix('typing')->name('typing.')->middleware('section:typing')->group(function () {
            // Dashboard
            Route::get('/', [\App\Http\Controllers\Admin\Typing\DashboardController::class, 'index'])->name('dashboard');

            // Typing Services CRUD
            Route::resource('services', \App\Http\Controllers\Admin\Typing\ServiceController::class);
            Route::patch('services/{service}/toggle-status', [\App\Http\Controllers\Admin\Typing\ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
            Route::patch('services/{service}/toggle-featured', [\App\Http\Controllers\Admin\Typing\ServiceController::class, 'toggleFeatured'])->name('services.toggle-featured');
            Route::post('services/reorder', [\App\Http\Controllers\Admin\Typing\ServiceController::class, 'reorder'])->name('services.reorder');

            // Family Visa (Emirates and Visa Types)
            Route::prefix('family-visa')->name('family-visa.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'index'])->name('index');
                Route::get('/create', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'store'])->name('store');
                Route::get('/{family_visa}', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'show'])->name('show');
                Route::get('/{family_visa}/edit', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'edit'])->name('edit');
                Route::put('/{family_visa}', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'update'])->name('update');
                Route::delete('/{family_visa}', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'destroy'])->name('destroy');
                Route::patch('/{family_visa}/toggle-active', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'toggleActive'])->name('toggle-active');

                // Visa Types within Emirate
                Route::get('/{family_visa}/types/create', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'createType'])->name('types.create');
                Route::post('/{family_visa}/types', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'storeType'])->name('types.store');
                Route::get('/{family_visa}/types/{visaType}/edit', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'editType'])->name('types.edit');
                Route::put('/{family_visa}/types/{visaType}', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'updateType'])->name('types.update');
                Route::delete('/{family_visa}/types/{visaType}', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'destroyType'])->name('types.destroy');
                Route::patch('/{family_visa}/types/{visaType}/toggle-active', [\App\Http\Controllers\Admin\Typing\FamilyVisaController::class, 'toggleTypeActive'])->name('types.toggle-active');
            });

            // Typing Inquiries
            Route::prefix('inquiries')->name('inquiries.')->group(function () {
                Route::post('bulk-mark-read', [\App\Http\Controllers\Admin\Typing\InquiryController::class, 'bulkMarkRead'])->name('bulk-mark-read');
                Route::delete('bulk-delete', [\App\Http\Controllers\Admin\Typing\InquiryController::class, 'bulkDelete'])->name('bulk-delete');
            });
            Route::resource('inquiries', \App\Http\Controllers\Admin\Typing\InquiryController::class)->only(['index', 'show', 'destroy']);
            Route::patch('inquiries/{inquiry}/mark-read', [\App\Http\Controllers\Admin\Typing\InquiryController::class, 'markRead'])->name('inquiries.mark-read');
            Route::patch('inquiries/{inquiry}/mark-responded', [\App\Http\Controllers\Admin\Typing\InquiryController::class, 'markResponded'])->name('inquiries.mark-responded');
            Route::patch('inquiries/{inquiry}/close', [\App\Http\Controllers\Admin\Typing\InquiryController::class, 'close'])->name('inquiries.close');

            // Settings
            Route::get('settings', [\App\Http\Controllers\Admin\Typing\SettingController::class, 'index'])->name('settings.index');
            Route::put('settings/company', [\App\Http\Controllers\Admin\Typing\SettingController::class, 'updateCompany'])->name('settings.update-company');
            Route::put('settings/seo', [\App\Http\Controllers\Admin\Typing\SettingController::class, 'updateSeo'])->name('settings.update-seo');
            Route::put('settings/social', [\App\Http\Controllers\Admin\Typing\SettingController::class, 'updateSocial'])->name('settings.update-social');
            Route::put('settings/contact', [\App\Http\Controllers\Admin\Typing\SettingController::class, 'updateContact'])->name('settings.update-contact');
            Route::post('settings/clear-cache', [\App\Http\Controllers\Admin\Typing\SettingController::class, 'clearCache'])->name('settings.clear-cache');
        });

        // ==========================================
        // User Management (Global - All Admins)
        // ==========================================
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['create', 'store']);
        Route::patch('users/{user}/toggle-active', [\App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('users.toggle-active');

        // ==========================================
        // Office Locations (Global - All Admins)
        // ==========================================
        Route::resource('office-locations', \App\Http\Controllers\Admin\OfficeLocationController::class)->except(['show']);
        Route::patch('office-locations/{officeLocation}/toggle-active', [\App\Http\Controllers\Admin\OfficeLocationController::class, 'toggleActive'])->name('office-locations.toggle-active');
        Route::post('office-locations/reorder', [\App\Http\Controllers\Admin\OfficeLocationController::class, 'reorder'])->name('office-locations.reorder');

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
