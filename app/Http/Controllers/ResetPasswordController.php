<?php

namespace App\Http\Controllers;

use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class ResetPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
    }
    public function ForgotPassword(Request $request)
    {
        //         $data = $request->validate([
        // //'email' => 'required|email|exists:users',
        //            'email' => ['required,email,exists:users']
        //         ]);

        //         // Delete all old code that user send before.
        //         ResetCodePassword::where('email', $request->email)->delete();
        //         // Generate random code
        //         $data['code'] = mt_rand(100000, 999999);

        //         // Create a new code
        //         $codeData = ResetCodePassword::create($data);

        //         // Send email to user
        //         Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        //         return response(['message' => trans('passwords.sent')], 200);
    //     $data = $request->validate([
    //         'email' => 'required|email|exists:users',
    //     ]);
    //     // Check if the email exists in the database
    //     $user = User::where('email',$data['email'])->first();

    //     if (!$user) {
    //         return response(['message' => trans('Email not found, please register'), 'status' => 404]);
    //     }
    //     // Generate random code
    //     $code = mt_rand(100000, 999999);

    //     // Save the code in the database
    //     ResetCodePassword::updateOrCreate(
    //         ['email' => $data['email']],
    //         ['code' => $code]
    //     );

    //     // Send email to user
    //     Mail::to($data['email'])->send(new SendCodeResetPassword($code));

    //     return response(['message' => trans('we have send a code in your email'), 'status' => 200]);
    // }

// Validate the email format
$validator = Validator::make($request->all(), [
    'email' => 'required|email',
]);

// Check if validation fails
if ($validator->fails()) {
    return response(['message' => trans('Invalid email format'), 'status' => 400]);
}

$email = $request->input('email');

// Check if the email exists in the database
$user = User::where('email', $email)->first();

if (!$user) {
    return response(['message' => trans('Email not found, please register'), 'status' => 404]);
}

// Generate random code
$code = mt_rand(100000, 999999);

// Save the code in the database
ResetCodePassword::updateOrCreate(
    ['email' => $email],
    ['code' => $code]
);

// Send email to user
Mail::to($email)->send(new SendCodeResetPassword($code));

return response(['message' => trans('We have sent a code to your email'), 'status' => 200]);


    }

    public function  CheckCode(Request $request)
    {

        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);
        // Check if the code exists
        if (!$passwordReset) {
            return response()->json(['message' => 'Invalid email or code'], 422);
        }
        // Compare the provided code with the one stored in the database
        if ($passwordReset->code !== $request->code) {
            return response()->json(['message' => 'Invalid code'], 422);
        }
        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        return response([
            'code' => $passwordReset->code,
            'message' => trans('code_is_valid')
        ], 200);
    }
    public function resendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Check if there is an existing reset code for the user's email
        $passwordReset = ResetCodePassword::where('email', $request->email)->first();

        if ($passwordReset) {
            // If there's an existing reset code, delete it to generate a new one
            $passwordReset->delete();
        }

        // Generate a new random code
        $code = mt_rand(100000, 999999);

        // Save the new code in the database
        ResetCodePassword::create([
            'email' => $request->email,
            'code' => $code,
        ]);

        // Send email to user
        Mail::to($request->email)->send(new SendCodeResetPassword($code));

        return response()->json(['message' => 'Reset code resent to your email'], 200);
    }


    public function UserRestPassword(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:8|confirmed',
        ]);
        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        // find user's email 
        $user = User::firstWhere('email', $passwordReset->email);

        // update user password
        $user->update($request->only('password'));

        // delete current code 
        $passwordReset->delete();

        return response(['message' => 'password has been successfully reset'], 200);
    }
}
