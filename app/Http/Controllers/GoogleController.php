<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
 class GoogleController extends Controller{

 public function redirectToGoogle() { 
 return Socialite::driver('google')->redirect(); }

 public function handleGoogleCallback() {
    try {
        $user = Socialite::driver('google')->user();
        $finduser = User::where('google_id', $user->id)->first();
        
        if ($finduser) {
            Auth::login($finduser);
            return response()->json($finduser);
        } else {
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'password' => encrypt('123456thurayya')
            ]);
            Auth::login($newUser);
            return redirect('/home');
        }
    } catch (Exception $e) {
        dd($e->getMessage());
    }
}
     }