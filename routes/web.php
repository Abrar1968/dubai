<?php

use App\Http\Controllers\Public\HajjController;
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
Route::get('/contactus', [HajjController::class, 'contact'])->name('contactus');
Route::post('/contactus', [HajjController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/articles', [HajjController::class, 'articles'])->name('articles');
Route::get('/articles/{slug}', [HajjController::class, 'articleShow'])->name('blog.show');
Route::get('/packages/{slug}', [HajjController::class, 'packageShow'])->name('packages.show');
Route::post('/packages/{slug}/book', [\App\Http\Controllers\User\BookingController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('packages.book');

Route::get('/typing', function () {
    return Inertia::render('typing/typinghome');
})->name('typing.index');

// Typing Service pages
Route::get('/typing/services/immigration', function () {
    return Inertia::render('typing/services/Immigration');
})->name('typing.services.immigration');

Route::get('/typing/services/labour-ministry', function () {
    return Inertia::render('typing/services/LabourMinistry');
})->name('typing.services.labour-ministry');

Route::get('/typing/services/tasheel-services', function () {
    return Inertia::render('typing/services/TasheelServices');
})->name('typing.services.tasheel-services');

Route::get('/typing/services/domestic-workers-visa', function () {
    return Inertia::render('typing/services/DomesticWorkersVisa');
})->name('typing.services.domestic-workers-visa');

Route::get('/typing/services/family-visa-process', function () {
    return Inertia::render('typing/services/FamilyVisaProcess');
})->name('typing.services.family-visa-process');

// Family visa sub-pages
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

Route::get('/typing/services/health-insurance', function () {
    return Inertia::render('typing/services/HealthInsurance');
})->name('typing.services.health-insurance');

Route::get('/typing/services/ministry-of-interior', function () {
    return Inertia::render('typing/services/MinistryOfInterior');
})->name('typing.services.ministry-of-interior');

Route::get('/typing/services/certificate-attestation', function () {
    return Inertia::render('typing/services/CertificateAttestation');
})->name('typing.services.certificate-attestation');

Route::get('/typing/services/vat-registration', function () {
    return Inertia::render('typing/services/VATRegistration');
})->name('typing.services.vat-registration');

Route::get('/typing/services/ct-registration', function () {
    return Inertia::render('typing/services/CTRegistration');
})->name('typing.services.ct-registration');

Route::get('/typing/services/passport-renewal', function () {
    return Inertia::render('typing/services/PassportRenewal');
})->name('typing.services.passport-renewal');

Route::get('/typing/services/immigration-card', function () {
    return Inertia::render('typing/services/ImmigrationCard');
})->name('typing.services.immigration-card');

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

require __DIR__ . '/settings.php';
