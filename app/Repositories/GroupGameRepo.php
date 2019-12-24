<?php

namespace App\Repositories;

use App\Models\GroupGame;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\IGroupGame;
use Auth;

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
            return GroupGame::with('game', 'group', 'links')->where('group_id', $groupId)->paginate($itemsPerPage);
       }
       return GroupGame::with('game', 'group','links')->where('group_id', $groupId)->get();
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
