<?php

use App\Http\Controllers\Auth\AuthenticationController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/login/{social}', [AuthenticationController::class, 'getSocialRedirect']);
Route::get('/login/{social}/callback', [AuthenticationController::class, 'getSocialCallback']);


//vue router will handle all routing
Route::get('/{any}', function () {
    return view('home');
})->where('any', '.*');
