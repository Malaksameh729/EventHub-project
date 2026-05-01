<?php

namespace App\Services;

use App\Models\Event;

class EventService
{
    public function filter($request)
    {
        $query = Event::query()->with('category');

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->keyword . '%')
                  ->orWhere('location', 'LIKE', '%' . $request->keyword . '%')
                  ->orWhere('address', 'LIKE', '%' . $request->keyword . '%')
                  ->orWhere('venue_name', 'LIKE', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('city')) {
            $query->where('location', 'LIKE', '%' . $request->city . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('price_type')) {
            $query->where('price_type', $request->price_type);
        }
       if ($request->filled('slug')) {
    $query->whereHas('category', function ($q) use ($request) {
        $q->where('slug', strtolower($request->slug));
    });
}
        if ($request->filled('latitude') && $request->filled('longitude')) {

            $lat = $request->latitude;
            $lng = $request->longitude;
            $radius = $request->radius ?? 50;

            $query->select('events.*')
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
                ->orderBy('distance');

        } else {
            $query->latest();
        }


        return $query;
    }
}