<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('bookings:expire')->everyFiveMinutes();
Schedule::command('bookings:complete')->everyFifteenMinutes();
Schedule::command('bookings:disburse')->everyFifteenMinutes();
Schedule::command('bookings:reminder')->dailyAt('08:00');
Schedule::command('xendit:monitor-balance')->dailyAt('07:00');