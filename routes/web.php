<?php

use App\Http\Controllers\Public\HajjController;
use App\Http\Controllers\Public\TypingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/tour-travel', function () {
    return Inertia::render('tour&travel/tourhome');
})->name('tour-travel.index');

// ==========================================
// Hajj & Umrah Public Routes (Dynamic Data)
// ==========================================
Route::get('/hajjhome', [HajjController::class, 'home'])->name('hajjhome.index');
Route::get('/hajj-umrah', [HajjController::class, 'home'])->name('hajj-umrah.index');
Route::get('/hajj-umrah/team', [HajjController::class, 'team'])->name('hajj-umrah.team');
Route::get('/hajjpackage', [HajjController::class, 'hajjPackages'])->name('hajjpackage');
Route::get('/umrahpackage', [HajjController::class, 'umrahPackages'])->name('umrahpackage');
Route::get('/tourpackage', [HajjController::class, 'tourPackages'])->name('tourpackage');
Route::get('/contactus', [HajjController::class, 'contact'])->name('contactus');
Route::post('/contactus', [HajjController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/articles', [HajjController::class, 'articles'])->name('articles');
Route::get('/articles/{slug}', [HajjController::class, 'articleShow'])->name('blog.show');
Route::get('/packages/{slug}', [HajjController::class, 'packageShow'])->name('packages.show');
Route::post('/packages/{slug}/book', [\App\Http\Controllers\User\BookingController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('packages.book');

// ==========================================
// Typing Services Public Routes
// ==========================================
// Main typing pages
Route::get('/typing', [TypingController::class, 'home'])->name('typing.index');
Route::get('/typing/services', [TypingController::class, 'services'])->name('typing.services');
Route::get('/typing/contact', [TypingController::class, 'contact'])->name('typing.contact');
Route::post('/typing/contact', [TypingController::class, 'storeContact'])->name('typing.contact.store');

// Family visa type detail pages (dynamic from database)
Route::get('/typing/services/family-visa/{emirateSlug}/{typeSlug}', [TypingController::class, 'familyVisaType'])
    ->name('typing.services.family-visa.type');

// Family visa sub-pages (most specific routes first)
Route::get('/typing/services/family/new-residency', function () {
    return Inertia::render('typing/services/family/NewResidency');
})->name('typing.services.family.new-residency');

Route::get('/typing/services/family/residency-renewal', function () {
    return Inertia::render('typing/services/family/ResidencyRenewal');
})->name('typing.services.family.residency-renewal');

Route::get('/typing/services/family/new-born-baby', function () {
    return Inertia::render('typing/services/family/NewBornBaby');
})->name('typing.services.family.new-born-baby');

Route::get('/typing/services/family/cancellation', function () {
    return Inertia::render('typing/services/family/Cancellation');
})->name('typing.services.family.cancellation');

// Dynamic service detail route - catches all /typing/services/{slug} patterns
// The TypingController::service() method maps slugs to Vue components
Route::get('/typing/services/{slug}', [TypingController::class, 'service'])->name('typing.service');

// ==========================================
// User Dashboard Routes (Vue + Inertia)
// ==========================================
Route::prefix('user')->name('user.')->middleware(['auth', 'verified', 'user'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

    // Bookings
    Route::get('/bookings', [\App\Http\Controllers\User\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\User\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings', [\App\Http\Controllers\User\BookingController::class, 'store'])->name('bookings.store');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\User\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [\App\Http\Controllers\User\ProfileController::class, 'updatePassword'])->name('profile.password');
});

require __DIR__.'/settings.php';
