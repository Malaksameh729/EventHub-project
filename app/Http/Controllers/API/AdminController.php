<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class AdminController extends Controller
{
    public function pendingUsers()
    {
         $users = User::where('status', 0)->get();

    return ApiResponse::success($users, 'Pending users retrieved successfully');
    }

    public function updateStatus(Request $request, $id)
    {
         $request->validate([
        'status' => 'required|in:1,2'
    ]);

    $user = User::findOrFail($id);

    $user->status = (int) $request->status; 
    $user->save();

    return ApiResponse::success($user, 'User status updated successfully');
    }
}
