<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Helpers\ApiResponse;

class PasswordController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        $code = '1234';

        OtpCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'type' => 'reset_password',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        return ApiResponse::success(null, 'OTP sent successfully');
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        $otp = OtpCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('type', 'reset_password')
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return ApiResponse::error('Invalid or expired OTP', 400);
        }

        return ApiResponse::success(null, 'OTP is valid');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        $otp = OtpCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('type', 'reset_password')
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return ApiResponse::error('Invalid or expired OTP', 400);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        $otp->update([
            'used' => true
        ]);

        return ApiResponse::success(null, 'Password reset successfully');
    }
}
