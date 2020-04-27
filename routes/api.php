<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * All routes for a user
 */
Route::post('user/{id}', '\App\Http\Controllers\User\UserController@update');
Route::resource('user', '\App\Http\Controllers\User\UserController')->except([
    'create', 'edit'
]);

Route::get('profile', '\App\Http\Controllers\User\ProfileController@index');
Route::post('profile', '\App\Http\Controllers\User\ProfileController@update');
Route::delete('profile', '\App\Http\Controllers\User\ProfileController@destroy');

/**
 * All routes for a game
 */
Route::post('game/{id}', '\App\Http\Controllers\Game\GameController@update');
Route::resource('game', '\App\Http\Controllers\Game\GameController', ['parameters' => [
    'game' => 'id'
]])->except([
    'create', 'edit'
]);

Route::get('/basegame', '\App\Http\Controllers\Game\BaseGameController@index');

Route::get('unapprovedgames/{id}', '\App\Http\Controllers\Game\UnapprovedGameController@update');
Route::get('/unapprovedgames', '\App\Http\Controllers\Game\UnapprovedGameController@index');

Route::get('/approvedgames', '\App\Http\Controllers\Game\ApprovedGameController@index');

/**
 * All routes for a Groups
 */
Route::post('group/{id}', '\App\Http\Controllers\Group\GroupController@update');
Route::resource('group', '\App\Http\Controllers\Group\GroupController',  ['parameters' => [
    'group' => 'id'
]])->except([
    'create', 'edit'
]);
Route::get('user-group', '\App\Http\Controllers\Group\UserGroupsController@index');


Route::post('group/{group_id}/user/{id}', '\App\Http\Controllers\Group\GroupUsersController@update');
Route::resource('group/{group_id}/user', '\App\Http\Controllers\Group\GroupUsersController', ['parameters' => [
    'user' => 'id'
]]) ->except([
    'index', 'create', 'edit'
]);

//UnverifiedGroupUsersController
Route::post('unverified-group-user/{id}', '\App\Http\Controllers\Group\UnverifiedGroupUsersController@update');
Route::resource('unverified-group-user', '\App\Http\Controllers\Group\UnverifiedGroupUsersController')->except([
    'create', 'edit', 'store'
]);

//GroupGameController
Route::get('group/{group_id}/search-non-group-games', '\App\Http\Controllers\Group\GroupGameController@searchNonGroupGames');
Route::resource('group/{group_id}/group-game', '\App\Http\Controllers\Group\GroupGameController')->except([
    'show', 'create', 'edit', 'update'
]);

//GroupGameLinkController
Route::post('group/game/{group_game_id}/link/{id}', '\App\Http\Controllers\Group\GroupGameLinkController@update');
Route::resource('group/game/{group_game_id}/link', '\App\Http\Controllers\Group\GroupGameLinkController', ['parameters' => [
    'link' => 'id'
]])->except([
    'create', 'edit'
]);

Route::post('group/{group_id}/played/{id}', '\App\Http\Controllers\Played\PlayedGamesController@update');
Route::resource('group/{group_id}/played', '\App\Http\Controllers\Played\PlayedGamesController', ['parameters' => [
    'played' => 'id'
]]) ->except([
    'create', 'edit'
]);


//StatisticsController
Route::get('stats/group/{group_id}', '\App\Http\Controllers\Statistics\StatisticsController@groupStats');
Route::get('stats/group/{group_id}/year/{year}', '\App\Http\Controllers\Statistics\StatisticsController@groupYearStats');
Route::get('stats/group/{group_id}/game/{game_id}', '\App\Http\Controllers\Statistics\StatisticsController@groupGameStats');


//Merge game
Route::post('merge/{id}/game/{mergedId}', '\App\Http\Controllers\Game\MergeGameController@update');





