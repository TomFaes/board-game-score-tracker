<?php

namespace App\Repositories;

use App\Http\Middleware\Security\Group;
use App\Models\GroupGame;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\IGroupGame;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class GroupGameRepo extends Repository implements Contracts\IGroupGame
{
    public function getGroupGames()
    {
        return GroupGame::all();
    }
    /**
     * Get a game group
     * @return Object
     */
    public function getGroupGame($id)
    {
        return GroupGame::with('game', 'group', 'links')->find($id);
    }

    public function getGroupGameIds($groupId)
    {
        return  GroupGame::with('groupGames')->where('group_id', $groupId)->pluck('game_id');
    }

    public function getGamesOfGroup($groupId, $itemsPerPage = 0)
    {
        if($itemsPerPage > 0){
            $groupgames = GroupGame::with('game', 'group','links')->where('group_id', $groupId);
            $groupgames = $groupgames->join('games AS testGame' , 'group_games.game_id', '=', 'testGame.id')->orderBy('testGame.full_name')->select('group_games.*')->paginate($itemsPerPage);;
             return $groupgames;
            //return GroupGame::with('game', 'group', 'links')->where('group_id', $groupId)->paginate($itemsPerPage);
        }
        $groupgames = GroupGame::with('game', 'group','links')->where('group_id', $groupId)->orderBy('full_name');
        $groupgames = $groupgames->join('games' , 'group_games.game_id', '=', 'games.id')->orderBy('games.full_name')->select('group_games.*')->get();
        return $groupgames;
    }
    /***************************************************************************
     Next function will create or update the user object in de database
     **************************************************************************/

     /**
     * set the data of a game
     * @return Object
     */
    protected function setGroupGame(GroupGame $groupGame, array $data)
    {
        isset($data['group_id']) === true ? $groupGame->group_id = $data['group_id'] : "";
        isset($data['game_id']) === true ? $groupGame->game_id = $data['game_id'] : "";
        return $groupGame;
    }

    /**
     * Create a new group game
     * @return Object
     */
    public function create(Array $data)
    {
        $groupGame = new GroupGame();
        $groupGame = $this->setGroupGame($groupGame, $data);
        $groupGame->save();
        return $groupGame;
    }


    public function updateGroupGameIds($gameId, $newGameId)
    {
        GroupGame::where('game_id' , '=', $gameId)->update(
            [
                'game_id' => $newGameId
            ]
        );
        return 'group games updated';
    }

    /**
     * Delete a group game
     * @return Object
     */
    public function delete($groupGameId)
    {
        $gameGroup = $this->getGroupGame($groupGameId);
        return $gameGroup->delete();
    }
}
