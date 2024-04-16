<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
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

            try {
                if (!$token = auth()->guard('admin')->attempt($credentials)) {
                    return response()->json(['message' => 'Invalid email or password.', 'status'=> 401]);
                }
            } catch (Exception $e) {
                return response()->json([ 'error' =>'Failed to login, please try again.', 'status'=> 500]);
            }

            $admin = auth()->guard('admin')->user(); // Retrieve authenticated admin user
            $token = $token; // Retrieve token

            return response()->json(['admin' => $admin, 'token' => $token, 'status' => 'success' , 'message'=> 'You have been logged in successfully']);

            
    //     $credentials = $request->only('email', 'password');
    //     try {
    //         if (!$token = auth()->guard('admin')->attempt($credentials)) {
    //             return response()->json(['success' => false, 'error' => 'Some Error Message'], 401);
    //         }
    //     } catch (Exception $e) {
    //         return response()->json(['success' => false, 'error' => 'Failed to login, please try again.'], 500);
    //     }
    //         $admin = auth()->guard('admin')->user();
    //         // $admin = Auth::admin();
    //     return response()->json(['admin'=>$admin, 'status' => 'success']);
    // }

            // $credentials = $request->only('email', 'password');
            // $token = Auth::attempt($credentials);
            // if (!$token) {

            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Unauthorized',
            //     ], 401);
            // }

            // $admin = Auth::admin();
            // return response()->json([
            //     'status' => 'success',
            //     'user' => $admin,
            //     'authorisation' => [
            //         'token' => $token,
            //         'type' => 'bearer',
            //     ]
            // ]);
              }  catch (Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'message' => 'an exception occured', 'status' => 400]);
        }
    }
    public function register(Request $request)
    {
        try {
            $validating = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|ends_with:gmail.com,yahoo.com',
                'phone' => 'required|numeric|digits:10',
                'password' => 'required|string|min:8|max:16|confirmed',
            ]);
            if ($validating->fails()) {
                return response()->json(['data' => $validating->errors(), 'message' => 'incorrect info was entered or missing info', 'status' => 400]);
            }
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            // Auth::login($admin);
            // $token = Auth::attempt($request->only('email', 'password'));
            // $admin['token'] = $token;
            Auth::guard('admin')->login($admin);
            $token = Auth::guard('admin')->attempt($request->only('email', 'password'));
            $admin['token'] = $token;
            return $this->jsonResponse($admin, 'user created successfully', 200);
        } catch (Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'message' => 'an exception occured', 'status' => 400]);
        }
    }
    public function logout()
    {
        try {
            auth()->logout();
            // Auth::logout();
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
                // 'admin' => Auth::admin(),
                'admin'=>auth()->guard('admin')->user(),
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
            $admin = Admin::find(auth()->guard('admin')->user());
            return $this->jsonResponse($admin, 'this is your profile', 200);
        } catch (Exception $e) {
            return $this->jsonResponse('an exception occured', $e->getMessage(), 400);
        }
    }
}
