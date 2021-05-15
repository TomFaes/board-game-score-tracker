<?php

use Illuminate\Http\Request;

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\FavoriteUserGroupController;

use App\Http\Controllers\Game\GameController;
use App\Http\Controllers\Game\BaseGameController;
use App\Http\Controllers\Game\MergeGameController;
use App\Http\Controllers\Game\UnapprovedGameController;

use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\Group\GroupGameController;
use App\Http\Controllers\Group\GroupUsersController;
use App\Http\Controllers\Group\UserGroupsController;
use App\Http\Controllers\Group\GroupGameLinkController;

use App\Http\Controllers\Played\PlayedGamesController;

use App\Http\Controllers\Statistics\StatisticsController;

//All routes for profiles
Route::get('profile', [ProfileController::class, 'index']);
Route::post('profile', [ProfileController::class, 'update']);

// All routes for a game
Route::get('game', [GameController::class, 'index']);
Route::post('game', [GameController::class, 'store']);
Route::post('game/{id}', [GameController::class, 'update']);

Route::get('basegame', [BaseGameController::class, 'index']);

Route::post('unapprovedgames/{id}', [UnapprovedGameController::class, 'update']);
Route::get('unapprovedgames', [UnapprovedGameController::class, 'index']);

// All routes for a Groups
Route::get('group', [GroupController::class, 'index']);
Route::post('group', [GroupController::class, 'store']);
Route::get('group/{id}', [GroupController::class, 'show']);
Route::post('group/{id}', [GroupController::class, 'update']);

Route::get('user-group', [UserGroupsController::class, 'index']);

Route::post('group/{group_id}/user', [GroupUsersController::class, 'store']);
Route::post('group/{group_id}/user/{id}', [GroupUsersController::class, 'update']);
Route::post('join_group', [GroupUsersController::class, 'joinGroup']);
Route::post('group/{group_id}/user/{id}/regenerate_code', [GroupUsersController::class, 'regenerateGroupUserCode']);

Route::post('group/{group_id}/changeFavoriteGroup', [FavoriteUserGroupController::class, 'update']);

//GroupGameController
Route::get('group/{group_id}/search-non-group-games', [GroupGameController::class, 'searchNonGroupGames']);
Route::get('group/{group_id}/group-game', [GroupGameController::class, 'index']);
Route::post('group/{group_id}/group-game', [GroupGameController::class, 'store']);

//GroupGameLinkController
Route::post('group/game/{group_game_id}/link', [GroupGameLinkController::class, 'store']);
Route::post('group/game/{group_game_id}/link/{id}', [GroupGameLinkController::class, 'update']);

//PlayedGamesController
Route::get('group/{group_id}/played', [PlayedGamesController::class, 'index']);
Route::post('group/{group_id}/played', [PlayedGamesController::class, 'store']);
Route::post('group/{group_id}/played/{id}', [PlayedGamesController::class, 'update']);

//StatisticsController
Route::get('stats/group/{group_id}', [StatisticsController::class, 'groupStats']);
Route::get('stats/group/{group_id}/year/{year}', [StatisticsController::class, 'groupYearStats']);
Route::get('stats/group/{group_id}/game/{game_id}', [StatisticsController::class, 'groupGameStats']);

//Merge game
Route::post('merge/{id}/game/{mergedId}', [MergeGameController::class, 'update']);

//delete method doesn't work on 000webhost
Route::post('profile/delete', [ProfileController::class, 'destroy']);
Route::post('game/{id}/delete', [GameController::class, 'destroy']);
Route::post('group/{id}/delete', [GroupController::class, 'destroy']);
Route::post('group/{group_id}/user/{id}/delete', [GroupUsersController::class, 'destroy']);
Route::post('group/{group_id}/group-game/{id}/delete', [GroupGameController::class, 'destroy']);
Route::post('group/game/{group_game_id}/link/{id}/delete', [GroupGameLinkController::class, 'destroy']);
Route::post('group/{group_id}/played/{id}/delete', [PlayedGamesController::class, 'destroy']);
