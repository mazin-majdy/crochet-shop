<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\PrunePageViews;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// تشغيل الأمر أسبوعياً
Schedule::command('page-views:prune --days=30')
    ->weekly()
    ->name('prune-page-views')
    ->emailOutputOnFailure(config('mail.admin_email')); // اختياري
