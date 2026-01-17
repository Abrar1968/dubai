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

Route::get('/typing', function () {
    return Inertia::render('typing/typinghome');
})->name('typing.index');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==========================================
// User Dashboard Routes (Vue + Inertia)
// ==========================================
Route::prefix('user')->name('user.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

    // Bookings
    Route::get('/bookings', [\App\Http\Controllers\User\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\User\BookingController::class, 'show'])->name('bookings.show');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\User\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [\App\Http\Controllers\User\ProfileController::class, 'updatePassword'])->name('profile.password');
});

require __DIR__ . '/settings.php';
