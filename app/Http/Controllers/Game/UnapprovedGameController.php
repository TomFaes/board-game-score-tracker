<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameCollection;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Repositories\Contracts\IGame;

class UnapprovedGameController extends Controller
{
    protected $gameRepo;

    public function __construct(IGame $game) {
        $this->gameRepo = $game;
    }

    public function index()
    {
        return new GameCollection($this->gameRepo->getUnapprovedGames(20));
    }

    public function update (Game $game)
    {
        $game = $this->gameRepo->approveGame($game);
        return response()->json(new GameResource($game), 200);
    }
}
