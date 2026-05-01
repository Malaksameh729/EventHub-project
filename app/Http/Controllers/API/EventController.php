<?php

namespace App\Http\Controllers\API;

use App\Services\EventService;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function store(StoreEventRequest $request)
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('organizer')) {
            return ApiResponse::error('Only organizers can create events', 403);
        }

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('events', 'public')
            : null;

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'capacity' => $request->capacity,
            'location' => $request->location,
            'type' => $request->type,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'venue_name' => $request->venue_name,
            'address' => $request->address,
            'image' => $imagePath,
            'price_type' => $request->price_type,
            'price' => $request->filled('price') ? $request->price : 0,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'created_by' => $user->id,
        ]);
         $event->load('category');

        return ApiResponse::success(
            'Event created successfully',
            new EventResource($event)
        );
    }

     public function webHome()
    {
       $userId = Auth::id();

return ApiResponse::success('Home data', [
    'trending_events' => EventResource::collection(
        Event::withCount('bookings')
            ->withIsFavorite($userId)
            ->orderByDesc('bookings_count')
            
            ->take(5)
            ->get()
    ),

    'online_events' => EventResource::collection(
        Event::where('type', 'online')
            ->withIsFavorite($userId)
            ->latest()
            ->take(5)
            ->get()
    ),

    'latest_events' => EventResource::collection(
        Event::withIsFavorite($userId)
            ->latest()
            ->take(10)
            ->get()
    ),
]);
    }

    public function appHome(Request $request)
{
    $lat = $request->latitude;
    $lng = $request->longitude;
    $radius = $request->radius ?? 50;

    $userId = Auth::id();

    return ApiResponse::success('Home data', [
        'categories' => Category::select('id','name','slug')->get(),

        'upcoming_events' => EventResource::collection(
            Event::with('category')
                ->withIsFavorite($userId)
                ->where('start_time', '>=', now())
                ->orderBy('start_time')
                ->take(10)
                ->get()
        ),

        'nearby_events' => EventResource::collection(
            $request->filled('latitude') && $request->filled('longitude')
                ? Event::with('category')
                    ->withIsFavorite($userId)
                    ->select('events.*')
                    ->selectRaw("
                        (6371 * acos(
                            cos(radians(?)) *
                            cos(radians(latitude)) *
                            cos(radians(longitude) - radians(?)) +
                            sin(radians(?)) *
                            sin(radians(latitude))
                        )) AS distance
                    ", [$lat, $lng, $lat])
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->having('distance', '<=', $radius)
                    ->orderBy('distance')
                    ->take(10)
                    ->get()
                : collect([])
        ),
    ]);
}

    public function index(Request $request, EventService $eventService)
    {
       $userId = Auth::id();

    $query = $eventService->filter($request);

$query->with(['category', 'favorites' => function ($q) use ($userId) {
    $q->where('user_id', $userId);
}]);

$events = $query->paginate(10);
$events->load('category');

    return ApiResponse::success(
        $events->total() > 0 ? 'Events retrieved successfully' : 'No events found',
        [
            'data' => EventResource::collection($events),
            'pagination' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'total' => $events->total(),
            ]
        ]
        );
    }
  public function update(Request $request, $id)
{
    $user = Auth::user();

    if (!$user || !$user->hasRole('organizer')) {
        return ApiResponse::error('Only organizers can update events', 403);
    }

    $event = Event::findOrFail($id);
    if ($event->created_by != $user->id) {
        return ApiResponse::error('You can only update your own events', 403);
    }
    $oldData = $event->only([
        'title',
        'location',
        'start_time',
        'end_time'
    ]);
    $data = $request->only([
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'venue_name',
        'address'
    ]);
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('events', 'public');
    }

    if (empty(array_filter($data))) {
        return ApiResponse::error('No data to update', 400);
    }

    $changes = [];

    foreach ($oldData as $key => $oldValue) {
        if (isset($data[$key]) && $oldValue != $data[$key]) {
            $changes[] = $key;
        }
    }

    $event->update($data);

    if (!empty($changes)) {
        $users = \App\Models\Booking::where('event_id', $event->id)
            ->distinct()
            ->pluck('user_id');

        foreach ($users as $userId) {
            app(\App\Services\NotificationService::class)->send(
                $userId,
                'Event Updated',
                'Event has been updated: ' . implode(', ', $changes),
                'event'
            );
        }
    }
             $event->load('category');

    return ApiResponse::success(
        'Event updated successfully',
        $event
    );
}
public function show($id)
{

    $event = Event::with([
        'category',
        'speakers.user',
        'sponsors.user',
        'sponsors.package'
    ])->findOrFail($id);

    return response()->json([
        'success' => true,
        'data' => [
            'event' => new EventResource($event),

            'speakers' => $event->speakers->map(function ($s) {
                return [
                    'id' => $s->id,
                    'name' => $s->user->name,
                    'profile_picture' => $s->user->profile_picture,
                    'session_title' => $s->session_title,
                    'summary' => $s->summary,
                ];
            }),

            'sponsors' => $event->sponsors->map(function ($s) {
                return [
                    'id' => $s->id,
                    'name' => $s->user->name,
                    'logo' => $s->user->logo,
                ];
            }),
        ]
    ]);
}
public function destroy($id)
{
    $user = Auth::user();

    if (!$user || !$user->hasRole('organizer')) {
        return response()->json([
            'success' => false,
            'message' => 'Only organizers can delete events'
        ], 403);
    }

    $event = Event::findOrFail($id);

    if ($event->created_by != $user->id) {
        return response()->json([
            'success' => false,
            'message' => 'You are not allowed to delete this event'
        ], 403);
    }

    $event->delete();

    return response()->json([
        'success' => true,
        'message' => 'Event deleted successfully'
    ]);
}
}
