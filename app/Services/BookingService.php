<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Support\Str;
class BookingService
{
    public function createOrUpdate($user, $eventId, $tickets)
    {
    $event = Event::findOrFail($eventId);

    $bookedTickets = Booking::where('event_id', $event->id)
        ->where('status', 'confirmed')
        ->sum('tickets_count');

    $remaining = $event->capacity - $bookedTickets;

    if ($tickets > $remaining) {
        return [
            'success' => false,
            'message' => 'Not enough available seats',
            'available' => $remaining
        ];
    }

    $booking = Booking::where('user_id', $user->id)
        ->where('event_id', $eventId)
        ->first();

    if ($booking) {

        if ($booking->status === 'cancelled') {

            $booking->status = 'confirmed';
            $booking->tickets_count = $tickets;
            $booking->booking_code = strtoupper(Str::random(8));
            $booking->save();

            app(\App\Services\NotificationService::class)->send(
                $user->id,
                'Booking Re-Activated',
                'Your booking has been re-confirmed.',
                'booking'
            );

        } else {

            $booking->tickets_count += $tickets;
            $booking->save();

            app(\App\Services\NotificationService::class)->send(
                $user->id,
                'Booking Updated',
                'Your ticket count has been updated.',
                'booking'
            );
        }

    } else {

        $booking = Booking::create([
            'user_id' => $user->id,
            'event_id' => $eventId,
            'tickets_count' => $tickets,
            'booking_code' => strtoupper(Str::random(8)),
            'status' => 'confirmed',
        ]);

        app(\App\Services\NotificationService::class)->send(
            $user->id,
            'Booking Confirmed',
            'Your booking is confirmed. You can find your tickets in Tickets section.',
            'booking'
        );
    }

    return [
        'success' => true,
        'booking' => $booking
    ];
}
    

    public function generateQr($booking, $user)
    {
        return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data="
            . urlencode(json_encode([
                'name' => $user->name,
                'email' => $user->email,
                'booking_code' => $booking->booking_code,
                'event_id' => $booking->event_id
            ]));
    }
   public function myTickets($user)
{
    return Booking::with('event')
    ->where('user_id', $user->id)
    ->where('status', '!=', 'cancelled')
    ->latest()
    ->get()
        ->map(function ($booking) use ($user) {

            return [
                'id' => $booking->id,
                'event' => $booking->event ? [
                        'id' => $booking->event->id,
                        'title' => $booking->event->title,
                        'location' => $booking->event->location,
                        'start_time' => $booking->event->start_time,
                ] : null,
                'tickets_count' => $booking->tickets_count,
                'status' => $booking->status,
                'booking_code' => $booking->booking_code,
                'qr_code' => $this->generateQr($booking, $user),
            ];
        });
}

}