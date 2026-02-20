<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
	$this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jalankan setiap menit
Schedule::command('app:check-transaction-status')
	->everyMinute()
	->withoutOverlapping()
	->sendOutputTo(storage_path('logs/check-transaction-status.log'));

Schedule::command('digiflazz:sync-product 1 prepaid')
	// ->everyTenMinutes()
	->cron('*/10 * * * *')
	->withoutOverlapping()
	->runInBackground()
	->sendOutputTo(storage_path('logs/sync-digiflazz-product-prepaid.log'));
Schedule::command('digiflazz:sync-product 1 pasca')
	// ->everyTenMinutes()
	->cron('*/10 * * * *')
	->withoutOverlapping()
	->runInBackground()
	->sendOutputTo(storage_path('logs/sync-digiflazz-product-pasca.log'));

Schedule::command('sawit:rotate-logs')
	->dailyAt('03:00')
	// ->everyMinute()
	->withoutOverlapping()
	->runInBackground()
	->sendOutputTo(storage_path('logs/rotate-logs-sawit.log'));


// * * * * * cd /path-ke-project-anda && php artisan schedule:run >> /dev/null 2>&1
// * * * * * cd /path/project && php artisan schedule:run >> /path/project/cron-debug.log 2>&1
