<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupGameRequest;
use App\Http\Resources\GameCollection;
use App\Http\Resources\GroupGameCollection;
use App\Http\Resources\GroupGamePaginationCollection;
use App\Http\Resources\GroupGameResource;
use App\Models\Group;
use App\Models\GroupGame;
use App\Repositories\Contracts\IGame;
use App\Repositories\Contracts\IGroupGame;
use Illuminate\Http\Request;

class GroupGameController extends Controller
{
    protected $game;
    protected $groupGame;

    public function __construct(IGame $game, IGroupGame $groupGame)
    {
        $this->game = $game;
        $this->groupGame = $groupGame;
    }

    public function index(Group $group, Request $request)
    {
        $pageItems = $request->page_items ?? 0;
        $groupGames = $this->groupGame->getGamesOfGroup($group->id, $pageItems);

        if($pageItems > 0){
            return response()->json(new GroupGamePaginationCollection($groupGames), 200);
        }
        return response()->json(new GroupGameCollection($groupGames), 200);
    }

    public function store(Group $group, GroupGameRequest $request)
    {
        $groupGame = $this->groupGame->create($request->validated());
        return response()->json(new GroupGameResource($groupGame), 200);
    }

    public function destroy(Group $group, GroupGame $game)
    {
        if(count($game->links) > 0){
            return response()->json("There are still links in this group game", 200);
        }

        $this->groupGame->delete($game);
        return response()->json("Group game is deleted", 204);
    }

    public function searchNonGroupGames(Group $group)
    {
        $groupGames = $this->groupGame->getIdsOfGamesOfGroup($group->id);
        $games = $this->game->searchGamesNotInGroup($groupGames);
        return response()->json(new GameCollection($games), 200);
    }
}
