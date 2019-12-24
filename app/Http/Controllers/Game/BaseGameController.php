<?php

namespace App\Http\Controllers\Game;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Contracts\IGame;
use App\Validators\GameValidation;

class BaseGameController extends Controller
{
    /** @var App\Repositories\Contracts\IGame */
    protected $game;

    public function __construct(GameValidation $gameValidation, IGame $game)
    {
        $this->middleware('auth:api');
        $this->middleware('admin:Admin', ['except' => ['index']]);

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
        return response()->json($this->game->getBaseGames(), 200);
    }
}
