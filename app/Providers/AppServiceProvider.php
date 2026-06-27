<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Review;
use App\Observers\BookingObserver;
use App\Observers\ReviewObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Booking::observe(BookingObserver::class);
        Review::observe(ReviewObserver::class);
    }
}
