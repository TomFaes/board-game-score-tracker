<?php

namespace App\Http\Controllers\Game;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameCollection;
use App\Http\Resources\GameResource;
use Illuminate\Http\Request;

use App\Repositories\Contracts\IGame;

class UnapprovedGameController extends Controller
{

    /** @var App\Repositories\Contracts\IGame */
    protected $game;

    public function __construct(IGame $game) {
        $this->middleware('auth:api');
        $this->middleware('admin:Admin');

        $this->game = $game;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new GameCollection($this->game->getUnapprovedGames(20));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $game = $this->game->getGame($id);
        $game = $this->game->approveGame($game);
        return new GameResource($game);
    }
}
