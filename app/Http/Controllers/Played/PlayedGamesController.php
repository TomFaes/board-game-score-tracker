<?php

namespace App\Http\Controllers\Played;
use App\Http\Controllers\Controller;

use App\Repositories\Contracts\IPlayedGame;
use App\Repositories\Contracts\IPlayedGameScore;

use App\Http\Requests\PlayedGameRequest;

use App\Http\Resources\PlayedGamePaginationCollection;
use App\Http\Resources\PlayedGameResource;
use App\Http\Resources\PlayedGameScoreResource;
use App\Models\Group;
use App\Models\PlayedGame;

class PlayedGamesController extends Controller
{
    protected $playedGame;

    public function __construct(IPlayedGame $playedGame, IPlayedGameScore $playedGameScore) {
        $this->playedGame = $playedGame;
        $this->playedGameScore = $playedGameScore;
    }

    public function index(Group $group)
    {
        $played = $this->playedGame->getPlayedGroupGames($group->id, 20);
        return response()->json(new PlayedGamePaginationCollection($played), 200);
    }

    public function show(Group $group, PlayedGame $played){
        $playedGame = $this->playedGame->getPlayedGame($played->id);
        return response()->json(new PlayedGameScoreResource($playedGame), 200);
    }

    public function store(Group $group, PlayedGameRequest $request)
    {
        $playedGame = $this->playedGame->create($request->validated(), auth()->user()->id);

        if($request->expansions != ""){
            $this->playedGame->addExpansions(explode(',', $request->expansions), $playedGame);
        }
        $winnerId = $this->playedGameScore->createSetScores($request->players, $playedGame->id);
        $playedGame = $this->playedGame->setWinner($winnerId, $playedGame);

        return response()->json(new PlayedGameResource($playedGame), 200);
    }

    public function update(PlayedGameRequest $request, Group $group,  PlayedGame $played)
    {
        $playedGame = $this->playedGame->update($request->validated(), $played);
        if($request->expansions != ""){
            $this->playedGame->addExpansions(explode(',', $request->expansions), $playedGame);
        }else{
            $this->playedGame->addExpansions([], $playedGame);
        }

        $winnerId = $this->playedGameScore->updateSetScore($request->players, $playedGame->id);

        $playedGame = $this->playedGame->setWinner($winnerId, $playedGame);

        return response()->json(new PlayedGameResource($playedGame), 200);
    }

    public function destroy(Group $group,  PlayedGame $played)
    {
        $this->playedGame->delete($played);
        return response()->json("The played game is removed", 204);
    }
}
