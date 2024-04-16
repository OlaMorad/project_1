<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::controller(AdminController::class)->group(function () {
Route::post('login', 'login')->name('login');
Route::post('register', 'register')->name('register');
Route::post('logout', 'logout')->name('logout')->middleware('auth:admin');
Route::post('refresh', 'refresh')->name('refresh')->middleware('auth:admin');
Route::get('Profile', 'Profile')->name('admin.profile')->middleware('auth:admin');
});