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

echo "Availabilities count: " . $avails->count() . "\n";
echo "Availabilities with time != null: " . $avails->where('time', '!=', null)->count() . "\n";
$first = $avails->first();
if ($first) {
    echo "First availability time: " . var_export($first->time, true) . "\n";
}
