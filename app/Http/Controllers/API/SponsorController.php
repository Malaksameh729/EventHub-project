<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SponsorController extends Controller
{
    public function store(Request $request)
{
    $user = Auth::user();

    if (!$user || !$user->hasRole('sponsor')) {
        return response()->json([
            'success' => false,
            'message' => 'Only sponsors allowed'
        ], 403);
    }

    $request->validate([
        'package_id' => 'required|exists:packages,id'
    ]);

    $package = Package::findOrFail($request->package_id);

    $sponsorship = Sponsorship::create([
        'user_id' => $user->id,
        'package_id' => $package->id,
        'price' => $package->price,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Package selected',
        'data' => [
            'package' => $package->name,
            'price' => $package->price,
            'features' => [
                'logo_size' => $package->logo_size,
                'booth' => $package->booth,
                'speaking_slot' => $package->speaking_slot,
                'tickets' => $package->tickets,
            ]
        ]
    ]);
}
public function packages()
{
    return response()->json([
        'success' => true,
        'data' => Package::all()
    ]);
}
}
