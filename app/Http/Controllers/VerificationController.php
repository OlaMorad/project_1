<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\SendVerificationCode;
use App\Models\VerificationCode;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            // Generate a random verification code
            // $code = Str::random(6);
            $code = mt_rand(100000, 999999);

            // Save the code in the database
            VerificationCode::updateOrCreate([
                'email' => $request->email,
                'code' => $code,
            ]);
            // VerificationCode::updateOrCreate(
            //     ['email' => $data['email']],
            //     ['code' => $code]
            // );
            // Send email to user
            Mail::to($request->email)->send(new SendVerificationCode($code));

            return response(['message' => 'Verification code sent to your email', 'status' => 200]);
        } catch (Exception $e) {
            return $this->jsonResponse('an exception occured', $e->getMessage(), 400);
        }
    }


    public function  CheckCode(Request $request)
    {

        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
        ]);

        // find the code
        $emailCode = VerificationCode::firstWhere('code', $request->code);
        // Check if the code exists
        if (!$emailCode) {
            return response()->json(['message' => 'Invalid email or code'], 422);
        }
        // Compare the provided code with the one stored in the database
        if ($emailCode->code !== $request->code) {
            return response()->json(['message' => 'Invalid code'], 422);
        }
        // check if it does not expired: the time is one hour
        if ($emailCode->created_at > now()->addHour()) {
            $emailCode->delete();
            return response(['message' => trans('code_is_expire')], 422);
        }

        return response([
            'code' => $emailCode->code,
            'message' => trans('code_is_valid')
        ], 200);
    }
    public function resendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Check if there is an existing reset code for the user's email
        $emailCode = VerificationCode::where('email', $request->email)->first();

        if ($emailCode) {
            // If there's an existing reset code, delete it to generate a new one
            $emailCode->delete();
        }

        // Generate a new random code
        $code = mt_rand(100000, 999999);

        // Save the new code in the database
        VerificationCode::create([
            'email' => $request->email,
            'code' => $code,
        ]);

        // Send email to user
        Mail::to($request->email)->send(new SendVerificationCode($code));

        return response()->json(['message' => 'Reset code resent to your email'], 200);
    }
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        // Find the verification code
        $verificationCode = VerificationCode::where('code', $request->code)->first();
        if (!$verificationCode) {
            return response(['message' => 'Invalid verification code'], 422);
        }

        // Check if the code has expired (optional)
        // You can set an expiration time for the code and check it here

        // Mark the user as verified (optional)
        $user = User::where('email', $request->email)->first();


        // Check if the user has already been verified
        if ($user->email_verified_at) {
            return response()->json(['message' => 'Email has already been verified'], 400);
        }
        $user->update(['email_verified_at' => '1']);

        // Delete the verification code
        $verificationCode->delete();

        return response(['message' => 'Email verified successfully'], 200);
    }
}
