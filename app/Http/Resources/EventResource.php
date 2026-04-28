<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category?->name,
            'capacity' => $this->capacity,
            'location' => $this->location,
            'type' => $this->type,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'venue_name' => $this->venue_name,
            'address' => $this->address,
            'image_url' => $this->when($this->image, fn() => asset('storage/' . $this->image)),
            'price_type' => $this->price_type,
            'price' => $this->price_type === 'paid' ? (float) $this->price : null,
             'distance' => isset($this->distance)
                ? round($this->distance, 2)
                : null,
             'is_favorite' => $user
        ? $this->favorites()
            ->where('user_id', $user->id)
            ->exists()
        : false,
        ];
    }
}
