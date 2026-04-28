<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
class FavoriteController extends Controller
{
    public function toggle($eventId)
    {
        $user = Auth::user();

        $favorite = Favorite::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'success' => true,
                'message' => 'Removed from favorites'
            ]);
        }

        Favorite::create([
            'user_id' => $user->id,
            'event_id' => $eventId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Added to favorites'
        ]);
    }

    public function index()
    {
        $user = Auth::user();

        $favorites = Favorite::where('user_id', $user->id)->with('event')->get()->pluck('event');
        return response()->json([
            'success' => true,
            'count' => $favorites->count(),
            'data' => $favorites
        ]);
    }

    public function count()
    {
        $user = Auth::user();

        $count = Favorite::where('user_id', $user->id)->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}
