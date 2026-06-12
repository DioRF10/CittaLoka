<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$avails = App\Models\ExperienceAvailability::whereNull('time')->get();
$hour = 9;
foreach ($avails as $a) {
    try {
        $a->time = sprintf("%02d:00:00", $hour);
        $a->save();
        $hour++;
        if ($hour > 17) $hour = 9;
    } catch (\Exception $e) {
        $a->delete(); // delete if duplicate
    }
}
echo "Done.";
