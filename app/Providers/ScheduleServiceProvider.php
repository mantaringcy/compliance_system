<?php

namespace App\Providers;

use App\Console\Commands\SendComplianceReminders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class ScheduleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Schedule $schedule): void
    {
        $schedule->command(SendComplianceReminders::class)->dailyAt('09:00');
        // $schedule->command(SendComplianceReminders::class)->everyMinute();
    }
}
