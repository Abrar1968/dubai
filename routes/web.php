<?php

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

Route::get('/hajjhome', function () {
    return Inertia::render('hajj&umrah/hajjhome');
})->name('hajjhome.index');

Route::get('/hajj-umrah', function () {
    return Inertia::render('hajj&umrah/hajjhome');
})->name('hajj-umrah.index');

Route::get('/hajj-umrah/team', function () {
    return Inertia::render('hajj&umrah/team');
})->name('hajj-umrah.team');

Route::get('/hajjpackage', function () {
    return Inertia::render('hajj&umrah/hajjpackage');
})->name('hajjpackage');

Route::get('/umrahpackage', function () {
    return Inertia::render('hajj&umrah/umrahpackage');
})->name('umrahpackage');

Route::get('/contactus', function () {
    return Inertia::render('hajj&umrah/contactus');
})->name('contactus');

Route::get('/articles', function () {
    return Inertia::render('hajj&umrah/articles');
})->name('articles');

Route::get('/articles/{slug}', function ($slug) {
    return Inertia::render('hajj&umrah/article_detail', ['slug' => $slug]);
})->name('blog.show');

Route::get('/typing', function () {
    return Inertia::render('typing/typinghome');
})->name('typing.index');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==========================================
// User Dashboard Routes (Blade)
// ==========================================
Route::prefix('user')->name('user.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

    // Bookings
    Route::get('bookings', [\App\Http\Controllers\User\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [\App\Http\Controllers\User\BookingController::class, 'show'])->name('bookings.show');

    // Profile
    Route::get('profile', [\App\Http\Controllers\User\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [\App\Http\Controllers\User\ProfileController::class, 'updatePassword'])->name('profile.password');
});

require __DIR__ . '/settings.php';
