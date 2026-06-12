<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$experience = App\Models\Experience::with([
    'availabilities' => function ($q) {
        $q->where('date', '>=', now()->toDateString())
          ->where('is_blocked', false)
          ->orderBy('date')
          ->orderBy('time');
    },
])->where('status', 'active')->firstOrFail();

$avails = $experience->availabilities;

$availableDates = $avails
    ->where('is_blocked', false)
    ->where('time', '!=', null)
    ->pluck('date')
    ->map(fn($d) => $d->format('Y-m-d'))
    ->unique()
    ->values();

echo "AVAILABLE DATES JSON:\n";
echo json_encode($availableDates);
