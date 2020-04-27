<?php

namespace App\Repositories;

use App\Models\PlayedGame;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\IPlayedGame;
use Auth;

class PlayedGameRepo extends Repository implements Contracts\IPlayedGame
{
    /**
     * Get all the played games
     * @return Object
     */
    public function getPlayedGames($itemsPerPage = 0)
    {
        if($itemsPerPage > 0){
             return PlayedGame::with(['group', 'game', 'winner'])->OrderBy('date', 'desc')->paginate($itemsPerPage);
        }
        return PlayedGame::with(['group', 'game', 'winner'])->OrderBy('date', 'desc')->get();
    }

    /**
     * Get a played game
     * @return Object
     */
    public function getPlayedGame($id)
    {
        return PlayedGame::with(['group', 'game', 'winner'])->find($id);
    }

    public function getPlayedGroupGames($groupId, $itemsPerPage = 0)
    {
        if($itemsPerPage > 0){
             return PlayedGame::with(['group', 'game', 'winner', 'winner.user', 'expansions', 'scores', 'scores.groupUser', 'scores.groupUser.user' ])->where('group_id', $groupId)->OrderBy('date', 'desc')->paginate($itemsPerPage);
        }
        //return PlayedGame::with(['group', 'game', 'winner','expansions'])->where('group_id', $groupId)->OrderBy('date', 'desc')->get();
        return PlayedGame::with(['group', 'game', 'winner', 'winner.user','expansions', 'scores', 'scores.groupUser.user'])->where('group_id', $groupId)->OrderBy('date', 'desc')->get();
    }



    /***************************************************************************
     Next function will get stats data
     **************************************************************************/


    public function getStatPlayedGroupGames($groupId)
    {
        $playedGames = PlayedGame::where('played_games.group_id', $groupId);
        $playedGames =  $playedGames->join('games' , 'played_games.game_id', '=', 'games.id')->orderBy('games.full_name', 'asc');
        $playedGames =  $playedGames->join('played_games AS played' , 'played_games.id', '=', 'played.id')->orderBy('played.date', 'desc')->get('played_games.*');
        return $playedGames;
    }

    public function getStatPlayedGroupYearGames($groupId, $year = 2020)
    {
        $playedGames = PlayedGame::with(['group', 'group.groupUsers', 'scores'])->where('played_games.group_id', $groupId)->whereYear('played_games.date', $year);
        $playedGames =  $playedGames->join('games' , 'played_games.game_id', '=', 'games.id')->orderBy('games.full_name', 'asc');
        $playedGames =  $playedGames->join('played_games AS played' , 'played_games.id', '=', 'played.id')->orderBy('played.date', 'desc')->get('played_games.*');
        return $playedGames;
    }

    public function getStatPlayedGame($groupId, $gameId)
    {
        $playedGames = PlayedGame::with(['group', 'group.groupUsers', 'scores'])->where('played_games.group_id', $groupId)->where('played_games.game_id', $gameId);
        $playedGames =  $playedGames->join('games' , 'played_games.game_id', '=', 'games.id')->orderBy('games.full_name', 'asc');
        $playedGames =  $playedGames->join('played_games AS played' , 'played_games.id', '=', 'played.id')->orderBy('played.date', 'desc')->get('played_games.*');
        return $playedGames;
    }






    /***************************************************************************
     Next function will create or update the user object in de database
     **************************************************************************/

     /**
     * set the data of a game
     * @return Object
     */
    protected function setPlayedGame(PlayedGame $played, array $data)
    {
        isset($data['group_id']) === true ? $played->group_id = $data['group_id'] : "";
        isset($data['game_id']) === true ? $played->game_id = $data['game_id'] : "";
        isset($data['winner_id']) === true ? $played->winner_id = $data['winner_id'] : "";
        isset($data['date']) === true ? $played->date = $data['date'] : "";
        isset($data['time_played']) === true ? $played->time_played = $data['time_played'] : "";
        isset($data['remarks']) === true ? $played->remarks = $data['remarks'] : "";
        return $played;
    }

    /**
     * Create a new game
     * @return Object
     */
    public function create(Array $data, $userId = "")
    {
        $played = new PlayedGame();
        $played = $this->setPlayedGame($played, $data);
        $played->creator_id = $userId;
        $played->save();
        return $played;
    }

    /**
     * Update a game
     * @return Object
     */
    public function update(Array $data, $id)
    {
        $played = $this->getPlayedGame($id);
        $played = $this->setPlayedGame($played, $data);
        $played->save();
        return $played;
    }

    public function updateGameIds($gameId, $newGameId){
        PlayedGame::where('game_id' , '=', $gameId)->update(
            [
                'game_id' => $newGameId
            ]
        );
        return 'Played games updated';
    }

    public function setWinner($winnerId, $id){
        $played = $this->getPlayedGame($id);
        $played->winner_id = $winnerId;
        $played->save();
        return $played;

    }

    /**
     * Delete a game
     * @return Object
     */
    public function delete($id)
    {
        $played = $this->getPlayedGame($id);
        $played->expansions()->detach();
        return $played->delete();
    }

    public function addExpansions(Array $data, $id)
    {
            $played = $this->getPlayedGame($id);
            $played->expansions()->sync($data);
            return $played;
    }
}
