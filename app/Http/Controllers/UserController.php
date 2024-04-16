<?php

namespace App\Http\Controllers;

use App\Mail\SendVerificationCode;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\VerificationCode;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Contracts\Service\Attribute\Required;
use App\Controller\UserController\password_rule;

class UserController extends Controller
{

    use JsonResponse;
    public function login(Request $request)
    {
        try {
            $validating = Validator::make($request->only(['email', 'password']), [
                'email' => 'required|string|email|ends_with:gmail.com,yahoo.com',
                'password' => 'required|string',
            ]);
            if ($validating->fails()) {
                return response()->json(['data' => $validating->errors(), 'message' => 'incorrect info was entered or missing info', 'status' => 400]);
            }
            $credentials = $request->only('email', 'password');

            $token = Auth::attempt($credentials);
            if (!$token) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    401
                ]);
            }

            $user = Auth::user();
            unset($user['verified_code']);
            if (is_null(Auth::user()->email_verified_at)){
                return response()->json(['message' => 'Email verification is required.'], 403);
            }
            return response()->json([
                'status' => '200',
                'message' => 'logged in successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'message' => 'an exception occured', 'status' => 400]);
        }
    }

    public function register(Request $request)
    {
        try {
            // Validate user input
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'phone' => 'unique:users',
                'password' => [
                    'required', 'confirmed','min:8',
                    // password_rule::min(8)->mixedCase()->numbers()->symbols()
                ],
               

            ]);
            // Create a new user
            $user = User::create([
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'verified_code' => $code = mt_rand(100000, 999999), // Generate verification token
            ]);

            // Send verification email
            Mail::to($user->email)->send(new SendVerificationCode($code));
            Auth::login($user);
            $token = Auth::attempt($request->only('email', 'password'));
            $user['token'] = $token;
            // Remove verified_code from the response
            unset($user['verified_code']);
            return response()->json(['data'=>$user, 'message' => 'User registered successfully. Please check your email for verification' ,'status'=> 'success']);
        } catch (Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'message' => 'an exception occured', 'status' => 400]);
        }
    }
    // public function register(Request $request)
    // {
    //     try {
    //         $validating = Validator::make($request->all(), [
    //             'name' => 'required|string|max:255',
    //             'email' => 'required|string|email|unique:users|max:255|ends_with:gmail.com,yahoo.com',
    //             'phone' => 'required|numeric|digits:10',
    //             'password' => 'required|string|min:8|max:16|confirmed',
    //         ]);
    //         if ($validating->fails()) {
    //             return response()->json(['data' => $validating->errors(), 'message' => 'incorrect info was entered or missing info', 'status' => 400]);
    //         }
    //         $code = mt_rand(100000, 999999);

    //         $user = User::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'phone' => $request->phone,
    //             'password' => Hash::make($request->password),
    //         ]);

    //         // Save the code in the database
    //         VerificationCode::updateOrCreate([
    //             'email' => $request->email,
    //             'code' => $code,
    //         ]);
    //         // Send verification email
    //         Mail::to($request->email)->send(new SendVerificationCode($code));
    //         Auth::login($user);
    //         $token = Auth::attempt($request->only('email', 'password'));
    //         $user['token'] = $token;
    //         return response()->json([$user,'message' => 'User registered successfully. Please check your email for verification.'], 201);
    //       //  return $this->jsonResponse($user, 'user created successfully', 200);

    // }
    // public function verifyEmail(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email|exists:verification_codes',
    //         'code' => 'required|string',
    //     ]);

    //     // Find the verification code
    //     $verificationCode = VerificationCode::where('email', $request->email)
    //         ->where('code', $request->code)
    //         ->first();

    //     if (!$verificationCode) {
    //         return response()->json(['message' => 'Invalid verification code'], 422);
    //     }

    // Create or update the user
    //     $user = User::updateOrCreate([
    //         'email' => $request->email,
    //         'verified' => true, // Mark the email as verified
    //         'name' => $request->name,
    //         'phone' => $request->phone,
    //         'password' => Hash::make($request->password)
    //     ]);

    //     // Delete the verification code
    //     $verificationCode->delete();

    //     return response()->json(['message' => 'Email verified successfully'], 200);
    // }
    // public function verifyEmail(Request $request)
    // {
    //     $request->validate([
    //       //  'email' => 'required',
    //         'code' => 'required|string'
    //     ]);
    //     //  $user = User::where('email', $request->email)->first();
    //     $verificationCode = User::where('verified_code', $request->code)->first();
    //     if (!$verificationCode) {
    //         return response()->json(['message' => 'Invalid verification code'], 400);
    //     }
    //     $user = User::where('email', $request->email)->first();
    //     $user->email_verified_at = now();
    //     //  $user->email_verified_token = null; // Mark email as verified
    //     $user->save();

    //     return response()->json(['message' => 'Email verified successfully'], 200);
    // }
  
    public function verifyEmail(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric',
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();
        if ($user->email_verified_at) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        // Check if user exists and the code matches
        if (!$user || $user->verified_code != $request->code) {
            return response()->json(['message' => 'Invalid email or code'], 400);
        }

        // Update user's verification status
        $user->email_verified_at = now();
        $user->verified_code = null;
        $user->save();

        return response()->json(['message' => 'Email verified successfully'], 200);
    }



    public function logout()
    {
        try {
            Auth::logout();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out',
            ]);
        } catch (Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'message' => 'an exception occured', 'status' => 400]);
        }
    }

    public function refresh()
    {
        try {
            return response()->json([
                'status' => 'success',
                'user' => Auth::user(),
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'message' => 'an exception occured', 'status' => 400]);
        }
    }

    public function Profile()
    {
        try {
            $user = User::find(auth()->user()->id);
            return $this->jsonResponse($user, 'this is your profile', 200);
        } catch (Exception $e) {
            return $this->jsonResponse('an exception occured', $e->getMessage(), 400);
        }
    }
}
