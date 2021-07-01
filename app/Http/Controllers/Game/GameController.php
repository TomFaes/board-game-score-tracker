<?php

namespace App\Http\Controllers\Game;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Contracts\IGame;

use App\Http\Requests\GamesRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\GameCollection;
use App\Models\Game;

class GameController extends Controller
{
     /** App\Repositories\Contracts\GameValidation */
    protected $gameValidation;

    /** App\Repositories\Contracts\IGame */
    protected $game;

    public function __construct(IGame $game) {
        $this->middleware('auth')->only('view');
        $this->middleware('auth:api')->except('view');

        $this->middleware('game:normal')->only('view');
        $this->middleware('game')->except('store');

        $this->game = $game;
    }

    public function view()
    {
        return "TEST";

        /*
        $pageItems = isset($request->page_items) === true ? $request->page_items : 0;
        return new GameCollection($this->game->getGames($pageItems));
*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageItems = $request->page_items ?? 0;
        return new GameCollection($this->game->getGames($pageItems));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GamesRequest $request)
    {
        $game = $this->game->create($request->all());
        if(auth()->user()->role == 'Admin'){
            $game = $this->game->approveGame($game);
        }
        return new GameResource($game);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(GamesRequest $request, $id)
    {
        $game = $this->game->update($request->all(), $id);
        return new GameResource($game);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json($this->game->delete($id), 204);
    }
}
