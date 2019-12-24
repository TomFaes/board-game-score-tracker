<?php

namespace App\Http\Controllers\Group;
use App\Http\Controllers\Controller;

use App\Group\GroupGame;
use App\Repositories\Contracts\IGame;
use App\Repositories\Contracts\IGroupGame;
use Illuminate\Http\Request;

class GroupGameController extends Controller
{
    protected $game;
    protected $groupGame;

    public function __construct(IGame $game, IGroupGame $groupGame)
    {
        $this->middleware('auth:api');
        $this->middleware('groupgame')->except('index', 'searchNonGroupGames');

        $this->game = $game;
        $this->groupGame = $groupGame;
    }

    public function index($groupId)
    {
        $groupGames = $this->groupGame->getGamesOfGroup($groupId, 20);
        return response()->json($groupGames, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $groupGame = $this->groupGame->create($request->all());
        return response()->json($groupGame, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GroupGame  $groupGame
     * @return \Illuminate\Http\Response
     */
    public function destroy($groupId, $id)
    {
        $this->groupGame->delete($id);
        return response()->json("Group game is deleted", 204);
    }

    public function searchNonGroupGames($groupId, Request $request){
        $groupGames = $this->groupGame->getGroupGameIds($groupId);
        $games = $this->game->searchGamesNotInGroup($groupGames, $request->search);
        return response()->json($games, 200);
    }
}
