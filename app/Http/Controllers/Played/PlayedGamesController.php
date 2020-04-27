<?php

namespace App\Http\Controllers\Played;
use App\Http\Controllers\Controller;

use App\Models\PlayedGame;
use Illuminate\Http\Request;

use App\Repositories\Contracts\IPlayedGame;
use App\Repositories\Contracts\IPlayedGameScore;
use App\Validators\PlayedGameValidation;

class PlayedGamesController extends Controller
{
    protected $playedGame;
    protected $validation;

    public function __construct(IPlayedGame $playedGame, PlayedGameValidation $playedGameValidation, IPlayedGameScore $playedGameScore) {
        $this->middleware('auth:api')->except('view');
        $this->middleware('playedgame')->except('store');

        $this->playedGame = $playedGame;
        $this->playedGameScore = $playedGameScore;
        $this->validation = $playedGameValidation;
    }

    public function index($groupId)
    {
        $played = $this->playedGame->getPlayedGroupGames($groupId, 20);
        return response()->json($played, 200);
    }

    public function store($groupId, Request $request)
    {
        $this->validation->validatePlayedGame($request);
        $playedGame = $this->playedGame->create($request->all(), auth()->user()->id);
        if($request['expansions'] != ""){
            $this->playedGame->addExpansions(explode(',', $request['expansions']), $playedGame->id);
        }
        $winnerId = $this->playedGameScore->createSetScores($request['player'], $playedGame->id);
        $playedGame = $this->playedGame->setWinner($winnerId, $playedGame->id);

        return response()->json($playedGame, 200);
    }

    public function show($groupId, $id)
    {
        $played = $this->playedGame->getPlayedGame($id);
        return response()->json($played, 200);
    }


    public function update(Request $request, $group, $id)
    {
        $this->validation->validatePlayedGame($request);
        $playedGame = $this->playedGame->update($request->all(), $id);
        if($request['expansions'] != ""){
            $this->playedGame->addExpansions(explode(',', $request['expansions']), $playedGame->id);
        }else{
            //sent empty array
            $this->playedGame->addExpansions([], $playedGame->id);
        }

        $winnerId = $this->playedGameScore->updateSetScore($request['player'], $playedGame->id);

        $playedGame = $this->playedGame->setWinner($winnerId, $playedGame->id);

        return response()->json($playedGame, 200);
    }

    public function destroy($group, $id)
    {
        $this->playedGame->delete($id);
        return response()->json("The played game is removed");
    }
}
