<?php

namespace App\Observers;

use App\Models\Review;

class ReviewObserver
{
    public function saved(Review $review): void
    {
        $this->recalculate($review);
    }

    public function deleted(Review $review): void
    {
        $this->recalculate($review);
    }

    private function recalculate(Review $review): void
    {
        $experience = $review->experience;

        if ($experience) {
            $stats = $experience->reviews()
                ->where('status', 'approved')
                ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total')
                ->first();

            $experience->rating_avg = round((float) ($stats->avg_rating ?? 0), 2);
            $experience->total_reviews = (int) ($stats->total ?? 0);
            $experience->saveQuietly();
        }

        $host = $review->host;

        if ($host) {
            $stats = $host->reviews()
                ->where('status', 'approved')
                ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total')
                ->first();

            $host->rating_avg = round((float) ($stats->avg_rating ?? 0), 2);
            $host->total_reviews = (int) ($stats->total ?? 0);
            $host->saveQuietly();
        }
    }
}
