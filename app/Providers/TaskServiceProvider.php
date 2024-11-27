<?php

namespace App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
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
    public function boot(): void
    {
        //
    }

    public function schedule(Schedule $schedule)
    {
        // A simple task that logs a message every minute
        $schedule->call(function () {
            \Illuminate\Support\Facades\Log::info('Task executed at ' . now());
        })->everyMinute();  // Run every minute
    }
}
