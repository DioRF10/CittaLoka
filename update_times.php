<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = App\Models\ExperienceAvailability::whereNull('time')->update(['time' => '09:00:00']);
echo "Updated $count rows.";
