<?php

namespace App\Http\Controllers\Played;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Contracts\IPlayedGame;
use App\Repositories\Contracts\IPlayedGameScore;

use App\Http\Requests\PlayedGameRequest;

use App\Http\Resources\PlayedGamePaginationCollection;
use App\Http\Resources\PlayedGameResource;
use App\Http\Resources\PlayedGameScoreResource;

class PlayedGamesController extends Controller
{
    protected $playedGame;

    public function __construct(IPlayedGame $playedGame, IPlayedGameScore $playedGameScore) {
        $this->middleware('auth:api')->except('view');
        $this->middleware('playedgame')->except('store');

        $this->playedGame = $playedGame;
        $this->playedGameScore = $playedGameScore;
    }

    public function index($groupId)
    {
        $played = $this->playedGame->getPlayedGroupGames($groupId, 20);
        return response()->json(new PlayedGamePaginationCollection($played), 200);
    }

    public function show($groupId, $id){
        $playedGame = $this->playedGame->getPlayedGame($id);
        return response()->json(new PlayedGameScoreResource($playedGame), 200);
    }

    public function store($groupId, PlayedGameRequest $request)
    {
        $playedGame = $this->playedGame->create($request->all(), auth()->user()->id);
        if($request['expansions'] != ""){
            $this->playedGame->addExpansions(explode(',', $request['expansions']), $playedGame->id);
        }
        $winnerId = $this->playedGameScore->createSetScores($request['player'], $playedGame->id);
        $playedGame = $this->playedGame->setWinner($winnerId, $playedGame->id);

        return response()->json(new PlayedGameResource($playedGame), 200);
    }

    public function update(PlayedGameRequest $request, $group, $id)
    {
        $playedGame = $this->playedGame->update($request->all(), $id);
        if($request['expansions'] != ""){
            $this->playedGame->addExpansions(explode(',', $request['expansions']), $playedGame->id);
        }else{
            //sent empty array
            $this->playedGame->addExpansions([], $playedGame->id);
        }

        $winnerId = $this->playedGameScore->updateSetScore($request['player'], $playedGame->id);

        $playedGame = $this->playedGame->setWinner($winnerId, $playedGame->id);

        return response()->json(new PlayedGameResource($playedGame), 200);
    }

    public function destroy($group, $id)
    {
        $this->playedGame->delete($id);
        return response()->json("The played game is removed", 204);
    }
}
