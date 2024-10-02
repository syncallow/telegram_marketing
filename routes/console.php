<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

\Illuminate\Support\Facades\Schedule::job(new \App\Jobs\TelegramCheckAuthStatusJob)->everyFifteenSeconds();
\Illuminate\Support\Facades\Schedule::job(new \App\Jobs\SendPostsJob)->hourly();