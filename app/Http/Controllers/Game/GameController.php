<?php

namespace App\Http\Controllers\Game;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Contracts\IGame;

use App\Http\Requests\GameRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\GameCollection;
use App\Http\Resources\GamePaginationCollection;

use App\Models\Game;

class GameController extends Controller
{
    protected $gameValidation;
    protected $gameRepo;

    public function __construct(IGame $game)
    {
        $this->gameRepo = $game;
    }

    public function index(Request $request)
    {
        $pageItems = $request->page_items ?? 0;
        if($pageItems > 0){
            return response()->json(new GamePaginationCollection($this->gameRepo->getGames($pageItems)), 200);
        }
        return response()->json(new GameCollection($this->gameRepo->getGames()), 200);
    }

    public function store(GameRequest $request)
    {
        $game = $this->gameRepo->create($request->validated());
        if(auth()->user()->role == 'Admin'){
            $game = $this->gameRepo->approveGame($game);
        }
        return response()->json(new GameResource($game), 200);
    }

    public function update(GameRequest $request, Game $game)
    {
        $game = $this->gameRepo->update($request->validated(), $game);
        return response()->json(new GameResource($game), 200);
    }

    public function destroy(Game $game)
    {
        if(count($game->expansions) > 0 || count($game->groupGames) > 0){
            $message = "This game can't be delete. There are still ".count($game->expansions)." expansions and ".count($game->groupGames)." group games connected to this game";
            return response()->json($message, 200);
        }
        return response()->json($this->gameRepo->delete($game), 204);
    }
}
