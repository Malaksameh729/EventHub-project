<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Helpers\ApiResponse;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            $user->profile_picture = $request->file('profile_picture')
                ->store('profiles', 'public');
        }

        $user->save();

        return ApiResponse::success($user, 'Profile updated successfully');
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return ApiResponse::success($user, 'User profile');
    }
}
