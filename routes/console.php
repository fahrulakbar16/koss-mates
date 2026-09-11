<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:handle-tranfer-request')->dailyAt('02:10');

Schedule::command('billing:create-monthly')
    ->dailyAt('07:00')
    ->withoutOverlapping();

Schedule::command('billing:send-reminders')
    ->dailyAt('08:00')
    ->withoutOverlapping();
