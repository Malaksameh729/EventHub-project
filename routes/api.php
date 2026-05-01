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

    // AUTH
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

    // EVENTS (public)
    Route::get('events', [EventController::class, 'index']);
    Route::get('events/{id}', [EventController::class, 'show']);

    // EVENTS (organizer)
    Route::middleware(['auth:api','role:organizer'])->group(function () {
        Route::post('events', [EventController::class, 'store']);
        Route::put('events/{id}', [EventController::class, 'update']);
        Route::delete('events/{id}', [EventController::class, 'destroy']);
    });

    // ADMIN
    Route::middleware(['auth:api','role:admin'])->group(function () {
        Route::get('admin/pending-users', [AdminController::class, 'pendingUsers']);
        Route::post('admin/users/{id}/status', [AdminController::class, 'updateStatus']);
    });

    // BOOKINGS
    Route::middleware('auth:api')->group(function () {
        Route::post('bookings', [BookingController::class, 'store']);
        Route::get('my-tickets', [BookingController::class, 'myTickets']);
        Route::post('bookings/{id}/cancel', [BookingController::class, 'cancel']);
    });

    // FAVORITES
    Route::middleware('auth:api')->group(function () {
        Route::post('favorites/{eventId}', [FavoriteController::class, 'toggle']);
        Route::get('favorites', [FavoriteController::class, 'index']);
        Route::get('favorites-count', [FavoriteController::class, 'count']);
    });

    // NOTIFICATIONS
    Route::middleware('auth:api')->group(function () {
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    });

    // PROFILE
    Route::middleware('auth:api')->group(function () {
        Route::get('me', [ProfileController::class, 'me']);
        Route::post('profile/update', [ProfileController::class, 'update']);
    });

    // SPEAKER
    Route::middleware('auth:api')->group(function () {
        Route::post('speaker/apply', [SpeakerController::class, 'store']);
    });

    // SPONSOR
    Route::middleware('auth:api')->group(function () {
        Route::get('sponsor/packages', [SponsorController::class, 'packages']);
        Route::post('sponsor/select', [SponsorController::class, 'store']);
    });

    // WEB
    Route::prefix('web')->group(function () {
        Route::get('home', [EventController::class, 'webHome']);
        Route::get('events', [EventController::class, 'webIndex']);
    });

    // APP
    Route::prefix('app')->middleware('auth:api')->group(function () {
        Route::get('home', [EventController::class, 'appHome']);
        Route::get('events', [EventController::class, 'appIndex']);
    });
});