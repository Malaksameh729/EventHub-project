<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Event;
use App\Models\Booking;
use App\Services\NotificationService;
use Carbon\Carbon;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Schedule::call(function () {

     $events = Event::where('reminder_sent', false)
    ->where('start_time', '>=', now()->addHours(2))
    ->where('start_time', '<=', now()->addHours(2)->addMinutes(1))
    ->get();

    foreach ($events as $event) {

        $users = Booking::where('event_id', $event->id)
            ->distinct()
            ->pluck('user_id');

        foreach ($users as $userId) {
            app(NotificationService::class)->send(
                $userId,
                'Event Reminder',
                "Your event '{$event->title}' starts in 2 hours at {$event->location}",
                'event'
            );
        }

        $event->update(['reminder_sent' => true]);
    }

})->everyFiveMinutes();