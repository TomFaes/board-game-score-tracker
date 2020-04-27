<?php

namespace App\Http\Controllers\Game;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Contracts\IGame;
use App\Validators\GameValidation;

class GameController extends Controller
{
     /** App\Repositories\Contracts\GameValidation */
    protected $gameValidation;

    /** App\Repositories\Contracts\IGame */
    protected $game;

    public function __construct(GameValidation $gameValidation, IGame $game) {
        $this->middleware('auth')->only('view');
        $this->middleware('auth:api')->except('view');

        $this->middleware('game:normal')->only('view');
        $this->middleware('game')->except('store');

        $this->gameValidation = $gameValidation;
        $this->game = $game;
    }
/*
    public function view()
    {
        return view('game.index');
    }
    */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageItems = isset($request->page_items) === true ? $request->page_items : 0;
        return response()->json($this->game->getGames($pageItems), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->gameValidation->validateGame($request);
        $game = $this->game->create($request->all());
        if(auth()->user()->role == 'Admin'){
            $game = $this->game->approveGame($game);
        }
        return response()->json($game, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($this->game->getGame($id), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->gameValidation->validateGame($request, $id);
        $game = $this->game->update($request->all(), $id);
        return response()->json($game, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy($gameId)
    {
        $this->game->delete($gameId);
        return response()->json("Game is deleted", 204);
    }
}
