<?php

namespace App\Http\Controllers\Game;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameCollection;
use Illuminate\Http\Request;

use App\Repositories\Contracts\IGame;

class BaseGameController extends Controller
{
    /** @var App\Repositories\Contracts\IGame */
    protected $game;

    public function __construct(IGame $game)
    {
        $this->middleware('auth:api');
        $this->middleware('admin:Admin', ['except' => ['index']]);

        $this->game = $game;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new GameCollection($this->game->getBaseGames());
    }
}
