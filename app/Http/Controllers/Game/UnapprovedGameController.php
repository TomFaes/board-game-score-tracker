<?php

namespace App\Http\Controllers\Game;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Contracts\IGame;
use App\Validators\GameValidation;

class UnapprovedGameController extends Controller
{

    /** @var App\Repositories\Contracts\IGame */
    protected $game;

    public function __construct(GameValidation $gameValidation, IGame $game) {
        $this->middleware('auth:api');
        $this->middleware('admin:Admin');

        $this->gameValidation = $gameValidation;
        $this->game = $game;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->game->getUnapprovedGames(20), 200);
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
        return response()->json($game, 201);
    }
}
