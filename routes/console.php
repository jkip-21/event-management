<?php

use App\Console\Commands\SendReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('app:send-reminders', function () {
    Artisan::call('app:send-reminders');
    $this->info('Send Reminders command executed successfully.');
})->purpose('Send notifications to attendees')->daily();
  