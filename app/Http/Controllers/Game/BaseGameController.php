<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameCollection;
use App\Repositories\Contracts\IGame;

class BaseGameController extends Controller
{
    protected $gameRepo;

    public function __construct(IGame $game)
    {
        $this->gameRepo = $game;
    }

    public function index()
    {
        return new GameCollection($this->gameRepo->getBaseGames());
    }
}
