<?php

use App\Http\Controllers\API\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\PasswordController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\SpeakerController;
use App\Http\Controllers\API\SponsorController;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password', [PasswordController::class, 'forgotPassword']);
        Route::post('verify-otp', [PasswordController::class, 'verifyOtp']);
        Route::post('reset-password', [PasswordController::class, 'resetPassword']);
        Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
        });
    });
    Route::middleware(['auth:api','role:admin'])->group(function () {
        Route::get('admin/pending-users', [AdminController::class, 'pendingUsers']);
        Route::post('admin/users/{id}/status', [AdminController::class, 'updateStatus']);
    });
   Route::middleware(['auth:api', 'role:organizer'])->group(function () {
    Route::apiResource('events', EventController::class)->only(['store']);
    Route::put('/events/{id}', [EventController::class, 'update']);
});
Route::prefix('web')->group(function () {
        Route::get('home', [EventController::class, 'webHome']);
        Route::get('events', [EventController::class, 'webIndex']);
    });
    Route::prefix('app')->middleware('auth:api')->group(function () {
        Route::get('home', [EventController::class, 'appHome']);
        Route::get('events', [EventController::class, 'appIndex']);
    });
Route::get('events', [EventController::class, 'index']);
Route::middleware(['auth:api'])->group(function () {
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('my-tickets', [BookingController::class, 'myTickets']);
    Route::post('bookings/{id}/cancel', [BookingController::class, 'cancel']);
});
Route::middleware(['auth:api'])->group(function () {
    Route::post('favorites/{eventId}', [FavoriteController::class, 'toggle']);
    Route::get('favorites', [FavoriteController::class, 'index']);
    Route::get('favorites-count', [FavoriteController::class, 'count']);
    Route::post('favorites/toggle/{eventId}', [FavoriteController::class, 'toggle']);
});
Route::middleware('auth:api')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [ProfileController::class, 'me']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
});
Route::middleware('auth:api')->group(function () {
    Route::post('speaker/apply', [SpeakerController::class, 'store']);
});
Route::middleware('auth:api')->group(function () {
    Route::get('sponsor/packages', [SponsorController::class, 'packages']);
    Route::post('sponsor/select', [SponsorController::class, 'store']);
});
});


