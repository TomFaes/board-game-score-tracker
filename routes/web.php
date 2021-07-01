<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthenticationController;

use App\Http\Controllers\Game\GameController;

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

Route::get('/', function () {
    return view('home');
});


//Test views
//Route::get('/view01', '\\App\Http\Controllers\HomeController@view01')->name('view01');

//TEST ROUTE Remove for Production
//Route::get('/test', '\\App\Http\Controllers\HomeController@view')->name('test');


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('login', [HomeController::class, 'index']);

Route::get('/logout', [AuthenticationController::class, 'logout']);
Route::get('/login/{social}', [AuthenticationController::class, 'getSocialRedirect'])->middleware('guest');;
Route::get('/login/{social}/callback', [AuthenticationController::class, 'getSocialCallback'])->middleware('guest');;


//Route::get('game', [GameController::class, 'view']);

//vue router will handle all routing
Route::get('/{any}', function () {
    return view('home');
})->where('any', '.*');
