<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\GroupGame;
use App\Repositories\Contracts\IGroupGame;


class GroupGameRepo extends Repository implements IGroupGame
{
    public function getGroupGame($id)
    {
        return GroupGame::with('game', 'group', 'links')->find($id);
    }

    public function getIdsOfGamesOfGroup($id)
    {
        return  GroupGame::with('groupGames')->where('group_id', $id)->pluck('game_id');
    }

    public function getGamesOfGroup($id, $itemsPerPage = 0)
    {
        if($itemsPerPage > 0){
            $groupgames = GroupGame::with('game', 'group','links')->where('group_id', $id);
            $groupgames = $groupgames->join('games AS testGame' , 'group_games.game_id', '=', 'testGame.id')->orderBy('testGame.full_name')->select('group_games.*')->paginate($itemsPerPage);;
             return $groupgames;
        }
        $groupgames = GroupGame::with('game', 'group','links')->where('group_id', $id)->orderBy('full_name');
        $groupgames = $groupgames->join('games' , 'group_games.game_id', '=', 'games.id')->orderBy('games.full_name')->select('group_games.*')->get();
        return $groupgames;
    }
    /***************************************************************************
     Next function will create or update the user object in de database
     **************************************************************************/
    public function create(Array $data)
    {
        $groupGame = GroupGame::create($data);
        return $groupGame;
    }

    /**
     * after a mergen you can update the old id with the new id.
     */
    public function updateGroupGameIds($gameId, $newGameId)
    {
        GroupGame::where('game_id' , '=', $gameId)->update(
            [
                'game_id' => $newGameId
            ]
        );
        return 'group games updated';
    }

    public function delete(GroupGame $game)
    {
        return $game->delete();
    }
}
