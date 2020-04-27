<?php

namespace App\Http\Controllers\Game;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Contracts\IGame;
use App\Services\GameService\MergeGameService;
use App\Validators\GameValidation;
use Illuminate\Container\Container;

class MergeGameController extends Controller
{

    /** @var App\Repositories\Contracts\IGame */
    protected $game;

    public function __construct(IGame $game) {
        $this->middleware('auth:api');
        $this->middleware('admin:Admin');

        $this->game = $game;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, $mergedId)
    {
        $container = Container::getInstance();
        $mergeService = $container->make(MergeGameService::class);
        $mergeService->mergeGame($id, $mergedId);
        return response()->json('Game is merged', 201);
    }
}
