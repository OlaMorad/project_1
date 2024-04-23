<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VerificationController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// auth for user 
Route::controller(UserController::class)->group(function () {

    Route::post('login', 'login')->name('login');
    Route::post('register', 'register')->name('register');
    Route::post('/verify-email', 'verifyEmail');
    Route::post('logout', 'logout')->name('logout')->middleware('auth');
    Route::post('refresh', 'refresh')->name('refresh')->middleware('auth');
    Route::get('Profile', 'Profile')->name('user.profile')->middleware('auth');
});
// login with google
Route::controller(GoogleController::class)->group(function () {
    Route::get('/auth/google', 'redirectToGoogle')->name('/auth/google');
    Route::get('/auth/google/callback', 'handleGoogleCallback')->name('/auth/google/callback');
});
// reset password api
Route::controller(ResetPasswordController::class)->group(function () {
    Route::post('password/email', 'ForgotPassword')->name('password.email');
    Route::post('/password/resend', 'resendCode')->name('resend.code');
    Route::post('password/check/code', 'CheckCode')->name('password.check.code');
    Route::post('password/reset', 'UserRestPassword')->name('password.rest');
});
// email verification api
Route::Post('verifyEmail', [UserController::class, 'verifyEmail']);
// Route::post('/sendVerificationCode', [VerificationController::class, 'sendVerificationCode']);
//Route::post('/verify', [VerificationController::class, 'verify']);

Route::controller(EventTypeController::class)->group(function () {
    Route::get('event_types/{id}', 'show');
    Route::get('show_all_event_type', 'show_all_event_type');
    Route::get('halls_by_eventType/{id}','halls');
});
Route::controller(CityController::class)->group(function () {
    Route::get('show_all_cities', 'show_all_cities');
    Route::get('halls_by_cities/{id}', 'halls');
    Route::post('add_city',  'store')->middleware('auth:admin');

});

//HALLS{{{{{{{{{{{{{{{}}}}}}}}}}}}}}}
//search for halls
Route::get('search', [SearchController::class, 'search']);
Route::controller(HallController::class)->group(function () {
    //get all Halls
    Route::get('show_all_halls', 'show_all_halls');
    //insert new hall
    Route::post('store', 'store')->name('store')->middleware('auth:admin');
    //delete hall
    Route::delete('halls/{id}', 'delete_hall')->middleware('auth:admin');
});
