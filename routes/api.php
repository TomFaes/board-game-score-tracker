<?php

use App\Http\Middleware\Security\Admin;
use App\Http\Middleware\Security\GroupSecurity;
use App\Http\Middleware\Security\PlayedGameSecurity;

use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\FavoriteUserGroupController;
use App\Http\Controllers\Game\GameController;
use App\Http\Controllers\Game\BaseGameController;
use App\Http\Controllers\Game\MergeGameController;
use App\Http\Controllers\Game\UnapprovedGameController;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\Group\GroupGameController;
use App\Http\Controllers\Group\GroupUsersController;
use App\Http\Controllers\Group\GroupsOfUserController;
use App\Http\Controllers\Group\GroupGameLinkController;
use App\Http\Controllers\Played\PlayedGamesController;
use App\Http\Controllers\Statistics\StatisticsController;

//Info: //delete method doesn't work on 000webhost
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(ProfileController::class)->prefix('profile')->group( function (){
        Route::get('/', 'index');
        Route::post('/', 'update');
        Route::post('/delete', 'destroy');
    });

    Route::middleware(Admin::class)->group(function(){
        Route::controller(GameController::class)->prefix('game')->group(function () {
            Route::get('/', 'index');
            //Route::post('/', 'store');
            Route::post('/{game}', 'update');
            Route::post('/{game}/delete', 'destroy');
        });

        Route::post('/merge/{game}/game/{merge_game}', [MergeGameController::class, 'update']);

        Route::controller(UnapprovedGameController::class)->prefix('unapprovedgames')->group(function() {
            Route::get('/', 'index');
            Route::post('/{game}', 'update');
        });
    });
    Route::post('game', [GameController::class, 'store']);
    Route::get('/basegame', [BaseGameController::class, 'index']);

    Route::get('/user-group', [GroupsOfUserController::class, 'index']);
    Route::post('/join_group', [GroupUsersController::class, 'joinGroup']);
    Route::post('/group', [GroupController::class, 'store']);
    Route::post('group/{group}/changeFavoriteGroup', [FavoriteUserGroupController::class, 'update']);

    Route::middleware(GroupSecurity::class)->group(function(){
        Route::controller(GroupController::class)->prefix('group')->group(function(){
            Route::get('/{group}', 'show');
            Route::post('/{group}', 'update');
            Route::post('/{group}/delete', 'destroy');
        });

        Route::controller(GroupUsersController::class)->prefix('group/{group}')->group(function (){
            Route::get('/users', 'index');
            Route::post('/user', 'store');
            Route::post('/user/{group_user}', 'update');
            Route::post('/user/{group_user}/regenerate_code', 'regenerateGroupUserCode');
            Route::post('/user/{group_user}/delete', 'destroy');
        });

        Route::controller(GroupGameController::class)->prefix('group/{group}')->group(function () {
            Route::get('/search-non-group-games', 'searchNonGroupGames');
            Route::get('/group-game', 'index');
            Route::post('/group-game', 'store');
            Route::post('/group-game/{game}/delete', 'destroy');
        });

        Route::controller(GroupGameLinkController::class)->prefix('group/{group}/game/{game}/link')->group(function (){
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::post('/{link}', 'update');
            Route::post('/{link}/delete', 'destroy');
        });

        Route::controller(StatisticsController::class)->prefix('stats/group/{group}')->group(function() {
            Route::get('/', 'groupStats');
            Route::get('/year/{year}', 'groupYearStats');
            Route::get('/game/{game}', 'groupGameStats');
        });
    });

    Route::middleware(PlayedGameSecurity::class)->group(function(){
        Route::controller(PlayedGamesController::class)->prefix('/group/{group}/played')->group(function(){
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/{played}', 'show');
            Route::post('/{played}', 'update');
            Route::post('/{played}/delete', 'destroy');
        });
    });
});
