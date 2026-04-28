<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Event::whereBetween('start_time', [
        Carbon::now()->addHours(2)->subMinutes(1),
        Carbon::now()->addHours(2)->addMinutes(1),
    ])->get();

    foreach ($events as $event) {

        foreach ($event->bookings as $booking) {

            app(\App\Services\NotificationService::class)->send(
                $booking->user_id,
                'Event Reminder',
                "Your event '{$event->title}' starts in 2 hours!",
                'event'
            );
        }
    }

    $this->info('Event reminders sent successfully');

    }
}
