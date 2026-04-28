<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SpeakerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpeakerController extends Controller
{
    public function store(Request $request)
{
    $user = Auth::user();

    if (!$user || !$user->hasRole('speaker')) {
        return response()->json([
            'success' => false,
            'message' => 'Only speakers can submit this form'
        ], 403);
    }

    $request->validate([
        'session_title' => 'required|string|max:255',
        'summary' => 'nullable|string',
        'duration' => 'nullable|string',
        'session_format' => 'nullable|string',
    ]);

    $application = SpeakerApplication::create([
        'user_id' => $user->id,
        'session_title' => $request->session_title,
        'summary' => $request->summary,
        'duration' => $request->duration,
        'session_format' => $request->session_format,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Application submitted successfully',
        'data' => $application
    ]);
}
}
