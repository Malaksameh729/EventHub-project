<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Helpers\ApiResponse;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $logoPath = null;
    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('logos', 'public');
    }
    $profilePicturePath = null;
if ($request->hasFile('profile_picture')) {
    $profilePicturePath = $request->file('profile_picture')->store('profiles', 'public');
}

    $cvPath = null;
    if ($request->hasFile('cv')) {
        $cvPath = $request->file('cv')->store('cvs', 'public');
    }


    $user = User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'phone'    => $data['phone'] ?? null,
        'password' => Hash::make($data['password']),
        'job_title' => $data['job_title'] ?? null,
        'company_name' => $data['company_name'] ?? null,
        'company_number' => $data['company_number'] ?? null,
        'specialty' => $data['specialty'] ?? null,
        'country' => $data['country'] ?? null,
        'city' => $data['city'] ?? null,
        'full_mailing_address' => $data['full_mailing_address'] ?? null,
        'profile_picture' => $profilePicturePath,
        'linkedin' => $data['linkedin'] ?? null,
        'bio' => $data['bio'] ?? null,
        'location' => $data['location'] ?? null,
        'topic_id' => $data['topic_id'] ?? null,
        'cv' => $cvPath,
        'logo' => $logoPath,
    ]);

    $user->assignRole($data['role']);

    $isAttendee = $data['role'] === 'attendee';

    $user->update([
        'status' => $isAttendee ? 1 : 0
    ]);

    if ($isAttendee) {
        $token = JWTAuth::fromUser($user);

        return ApiResponse::success([
            'user' => $user,
            'token' => $token
        ], 'User registered successfully', 201);
    }

    return ApiResponse::success([
        'user' => $user,
    ], 'Registered successfully, waiting for admin approval', 201);
    }

    public function login(LoginRequest $request)
{
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return ApiResponse::error('Invalid credentials', 401);
    }

    if ($user->status != 1) {
        return ApiResponse::error(
            'Your account is waiting for admin approval',
            403
        );
    }

    $token = JWTAuth::fromUser($user);

    return ApiResponse::success([
        'user' => $user,
        'token' => $token
    ], 'Login successful');
}
    public function logout()
    {
        JWTAuth::parseToken()->invalidate();
        return ApiResponse::success(null, 'Logged out successfully');
    }

    public function refresh()
    {
        $newToken = JWTAuth::parseToken()->refresh();
        return ApiResponse::success([
            'token' => $newToken
        ], 'Token refreshed successfully');
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return ApiResponse::success($user, 'User info retrieved successfully');
    }
}
