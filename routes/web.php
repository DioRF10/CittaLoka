<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Onboarding\TravelerOnboardingController;
use App\Http\Controllers\Onboarding\HostOnboardingController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Models\Experience;
use App\Http\Controllers\Host\HostDashboardController;
use App\Http\Controllers\Host\ExperienceFormController;
use App\Http\Controllers\Host\HostProfileController;
use App\Http\Controllers\HostPublicController;
use App\Http\Controllers\MemoryBookController;
use App\Http\Controllers\Host\MemoryBookFillController;
use App\Http\Controllers\XenditWebhookController;
use App\Http\Controllers\HostBankController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\SeasonalCalendarController;


Route::get('/', function () {
    $featuredExperiences = Experience::with(['host.user', 'kategori', 'photos'])
        ->where('status', 'active')
        ->orderBy('is_featured', 'desc')
        ->orderBy('rating_avg', 'desc')
        ->take(4)
        ->get();

    return view('pages.home', compact('featuredExperiences'));
})->name('home');

Route::get('/register', function () {
    return view('auth.choose-role');
})->name('register');

Route::post('/register/set-role', function (Request $request) {
    $role = $request->input('role');
    if (!in_array($role, ['host', 'user'])) {
        return back();
    }
    session(['register_role' => $role]);
    return redirect()->route('register.form');
})->name('register.set-role');

Route::get('/register/form', function () {
    return view('auth.register');
})->name('register.form');

Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('login')->with('status', 'Email berhasil terverifikasi! Silakan login dengan akun Anda.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordController::class, 'request'])
    ->middleware('guest')->name('password.request');

Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordController::class, 'email'])
    ->middleware('guest')->name('password.email');

Route::get('/forgot-password/check-inbox', [App\Http\Controllers\Auth\PasswordController::class, 'checkInbox'])
    ->middleware('guest')->name('password.check-inbox');

Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\PasswordController::class, 'reset'])
    ->middleware('guest')->name('password.reset');

Route::post('/reset-password', [App\Http\Controllers\Auth\PasswordController::class, 'store'])
    ->middleware('guest')->name('password.store');

Route::get('/auth/google/redirect', [App\Http\Controllers\Auth\GoogleController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'callback'])->name('auth.google.callback');

Route::get('/experiences', [ExperienceController::class, 'index'])->name('experiences.index');
Route::get('/experiences/{slug}/reviews', [ExperienceController::class, 'reviews'])->name('experiences.reviews');
Route::get('/experiences/{slug}', [ExperienceController::class, 'show'])
    ->name('experiences.show');
Route::get('/experiences/{slug}/times', [ExperienceController::class, 'getTimes'])->name('experiences.times');
Route::get('/hosts/{id}', [HostPublicController::class, 'show'])->name('hosts.show');
Route::get('/seasonal-calendar', [SeasonalCalendarController::class, 'index'])
    ->name('seasonal-calendar.index');
Route::get('/seasonal-calendar/{id}', [SeasonalCalendarController::class, 'show'])
    ->name('seasonal-calendar.show');

Route::get('/soul-match', [App\Http\Controllers\SoulMatchController::class, 'intro'])->name('soul-match.intro');
Route::get('/soul-match/quiz', [App\Http\Controllers\SoulMatchController::class, 'show'])->name('soul-match.quiz');
Route::post('/soul-match/quiz', [App\Http\Controllers\SoulMatchController::class, 'submit'])->name('soul-match.submit');
Route::get('/soul-match/results', [App\Http\Controllers\SoulMatchController::class, 'results'])->name('soul-match.results');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout/{slug}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{slug}', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{kode}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{kode}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{kode}/cancel-confirm', [BookingController::class, 'cancelConfirm'])
        ->name('bookings.cancel-confirm');
    Route::patch('/bookings/{kode}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{kode}/review', [App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/bookings/{kode}/review', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/bookings/{kode}/complaint', [App\Http\Controllers\ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/bookings/{kode}/complaint', [App\Http\Controllers\ComplaintController::class, 'store'])->name('complaints.store');

    Route::get('/notifications/{id}/click', [NotificationController::class, 'click'])
        ->name('notifications.click');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])
        ->name('notifications.read-all');
    Route::delete('/notifications/delete-all', [NotificationController::class, 'destroyAll'])
        ->name('notifications.delete-all');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/my-profile', [ProfileController::class, 'index'])->name('my-profile.index');
    Route::put('/my-profile', [ProfileController::class, 'update'])->name('my-profile.update');
    Route::post('/hosts/follow-toggle', [FollowController::class, 'toggle'])->name('hosts.follow-toggle');

    Route::get('/memory-books', [MemoryBookController::class, 'index'])
        ->name('memory-books.index');
    Route::get('/memory-book/{kode}', [MemoryBookController::class, 'show'])
        ->name('memory-book.show');

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/onboarding/tourist', [TravelerOnboardingController::class, 'index'])
        ->name('onboarding.traveler');
    Route::post('/onboarding/tourist/save', [TravelerOnboardingController::class, 'save'])
        ->name('onboarding.traveler.save');

    Route::get('/onboarding/host', [HostOnboardingController::class, 'index'])
        ->name('onboarding.host');
    Route::post('/onboarding/host/save', [HostOnboardingController::class, 'save'])
        ->name('onboarding.host.save');
    Route::post('/host/bank-account/verify', [HostBankController::class, 'verify'])
        ->name('host.bank-account.verify');

});

Route::middleware(['auth', 'verified', 'host'])->prefix('dashboard')->name('host.')->group(function () {

    Route::get('/', [HostDashboardController::class, 'index'])->name('dashboard');

    Route::get('/experiences/create', [ExperienceFormController::class, 'create'])->name('experiences.create');
    Route::post('/experiences', [ExperienceFormController::class, 'store'])->name('experiences.store');
    Route::get('/experiences/{id}/edit', [ExperienceFormController::class, 'edit'])->name('experiences.edit');
    Route::put('/experiences/{id}', [ExperienceFormController::class, 'update'])->name('experiences.update');
    Route::post('/experiences/{id}/submit-review', [ExperienceFormController::class, 'submitReview'])->name('experiences.submitReview');
    Route::delete('/experiences/photos/{photoId}', [ExperienceFormController::class, 'deletePhoto'])->name('experiences.deletePhoto');

    Route::get('/experiences', [HostDashboardController::class, 'experiences'])->name('experiences.index');
    Route::delete('/experiences/{id}', [HostDashboardController::class, 'deleteExperience'])->name('experiences.destroy');

    Route::get('/memory-books', [HostDashboardController::class, 'memoryBooks'])->name('memory-books.index');
    Route::get('/memory-books/{id}/fill', [MemoryBookFillController::class, 'show'])
        ->name('memory-books.fill');
    Route::put('/memory-books/{id}/fill', [MemoryBookFillController::class, 'update'])
        ->name('memory-books.fill.update');
    Route::delete('/memory-books/photos/{photoId}', [MemoryBookFillController::class, 'deletePhoto'])
        ->name('memory-books.photos.delete');

    Route::get('/bookings', [HostDashboardController::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{id}/detail', [HostDashboardController::class, 'bookingDetail'])->name('bookings.detail');

    // Complaints
    Route::get('/complaints/{kode}/create', [App\Http\Controllers\Host\HostComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints/{kode}', [App\Http\Controllers\Host\HostComplaintController::class, 'store'])->name('complaints.store');

    Route::get('/reviews', [App\Http\Controllers\Host\HostReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/reply', [App\Http\Controllers\Host\HostReviewController::class, 'reply'])->name('reviews.reply');

    Route::get('/availability', [HostDashboardController::class, 'availability'])->name('availability.index');
    Route::post('/availability', [HostDashboardController::class, 'storeAvailability'])->name('availability.store');
    Route::delete('/availability/{id}', [HostDashboardController::class, 'deleteAvailability'])->name('availability.delete');

    Route::get('/earnings/export', [HostDashboardController::class, 'exportPayouts'])
        ->name('earnings.export');
    Route::get('/earnings', [HostDashboardController::class, 'earnings'])->name('earnings');

    Route::get('/settings', [HostDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [HostDashboardController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/resubmit-ktp', [HostDashboardController::class, 'resubmitKtp'])->name('settings.resubmit-ktp');
    Route::post('/settings/resubmit-bank', [HostDashboardController::class, 'resubmitBank'])->name('settings.resubmit-bank');

    Route::get('/profile', [HostProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [HostProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/heritage', [HostProfileController::class, 'storeHeritage'])->name('profile.heritage.store');
    Route::delete('/profile/heritage/{id}', [HostProfileController::class, 'deleteHeritage'])->name('profile.heritage.delete');

});

Route::post('/webhooks/xendit/invoice', [XenditWebhookController::class, 'handleInvoice'])
    ->name('webhooks.xendit.invoice');

Route::post('/webhooks/xendit/disbursement', [XenditWebhookController::class, 'handleDisbursement'])
    ->name('webhooks.xendit.disbursement');