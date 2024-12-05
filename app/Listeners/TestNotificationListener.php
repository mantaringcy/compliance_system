<?php

namespace App\Listeners;

use App\Events\TestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TestNotificationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TestNotification $event): void
    {
        // Handle the event, e.g., log the message
        \Log::info('Test Notification: ' . $event->message);
    }
}
