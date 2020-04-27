<?php

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

//Route::get('/', 'HomeController@index');

//Test views
Route::get('/view01', '\\App\Http\Controllers\HomeController@view01')->name('view01');

//TEST ROUTE Remove for Production
Route::get('/test', '\\App\Http\Controllers\HomeController@view')->name('test');

//Show login form with all links to the social login options
Route::get('login/', 'Auth\LoginController@showLoginForm')->name('login');

//The social login links
Route::get( '/login/{social}', 'Auth\AuthenticationController@getSocialRedirect' )->middleware('guest');
Route::get( '/login/{social}/callback', 'Auth\AuthenticationController@getSocialCallback' )->middleware('guest');
Route::get( '/logout', 'Auth\AuthenticationController@logout' )->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/user', '\App\Http\Controllers\User\UserController@view')->name('user');
//Route::get('/profile', '\App\Http\Controllers\User\ProfileController@view')->name('profile');
//Route::get('/game', '\App\Http\Controllers\Game\GameController@view')->name('user');
Route::get('/group', '\App\Http\Controllers\Group\GroupController@view')->name('group');



