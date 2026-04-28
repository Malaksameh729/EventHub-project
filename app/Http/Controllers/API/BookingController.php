<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
     private $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'tickets_count' => 'nullable|integer|min:1'
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $tickets = $request->tickets_count ?? 1;

        $result = $this->bookingService->createOrUpdate($user, $request->event_id, $tickets);

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        $booking = $result['booking'];

        return response()->json([
            'success' => true,
            'message' => 'Booked successfully',
            'data' => [
                'booking' => $booking,
                'qr_code' => $this->bookingService->generateQr($booking, $user)
            ]
        ]);
    }
    public function myTickets()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    $bookings = $this->bookingService->myTickets($user);

    return response()->json([
        'success' => true,
        'data' => $bookings
    ]);
}
}