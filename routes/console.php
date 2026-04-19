<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('blogs:generate-ai-drafts')
    ->dailyAt((string) (config('blog.schedule_time') ?? config('blog_automation.schedule_time', '06:00')))
    ->withoutOverlapping();
