<?php

namespace App\Repositories;

use App\Models\PlayedGame;


class PlayedGameRepo extends Repository implements Contracts\IPlayedGame
{
    public function getPlayedGames($itemsPerPage = 0)
    {
        if($itemsPerPage > 0){
             return PlayedGame::with(['group', 'game', 'winner'])
                            ->OrderBy('date', 'desc')
                            ->paginate($itemsPerPage);
        }
        return PlayedGame::with(['group', 'game', 'winner'])
                        ->OrderBy('date', 'desc')
                        ->get();
    }

    public function getPlayedGame($id)
    {
        return PlayedGame::with(['group', 'game', 'winner'])->find($id);
    }

    public function getPlayedGroupGames($groupId, $itemsPerPage = 0)
    {
        if($itemsPerPage > 0){
            return PlayedGame::with(['group', 'game', 'winner', 'expansions', 'scores', 'creator'])
                            ->where('group_id', $groupId)
                            ->OrderBy('date', 'desc')
                            ->paginate($itemsPerPage);
        }
        return PlayedGame::with(['group', 'game', 'winner', 'expansions', 'scores', 'creator'])
                        ->where('group_id', $groupId)
                        ->OrderBy('date', 'desc')
                        ->get();
    }
    /***************************************************************************
     Next function will get stats data
     **************************************************************************/
    public function getStatPlayedGroupGames($groupId)
    {
        $playedGames = PlayedGame::where('played_games.group_id', $groupId)
                                    ->with(['game', 'scores', 'winner', 'creator', 'group', 'group.groupGames', 'expansions', 'expansions.expansions'])
                                    ->orderBy('date', 'desc')
                                    ->get();
        return $playedGames;
    }

    public function getStatPlayedGroupYearGames($groupId, $year = 2020)
    {
        $playedGames = PlayedGame::where('played_games.group_id', $groupId)
                                        ->with(['game', 'scores', 'winner', 'creator', 'group', 'group.groupUsers', 'expansions', 'expansions.expansions'])
                                        ->whereYear('played_games.date', $year)
                                        ->orderBy('date', 'desc')
                                        ->get();
        return $playedGames;
    }

    public function getStatPlayedGame($groupId, $gameId)
    {
        $playedGames = PlayedGame::where('played_games.group_id', $groupId)
                                        ->with(['game', 'scores', 'winner', 'creator', 'group', 'group.groupUsers', 'expansions', 'expansions.expansions'])
                                        ->where('played_games.game_id', $gameId)
                                        ->orderBy('date', 'desc')
                                        ->get();
        return $playedGames;
    }
    /***************************************************************************
     Next function will create or update the user object in de database
     **************************************************************************/

    public function create(Array $data, $userId = "")
    {
        $played = PlayedGame::create($data);
        $played->creator_id = $userId;
        $played->save();
        return $played;
    }

    public function update(Array $data, PlayedGame $played)
    {
        $played->update($data);
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

    public function setWinner($winnerId, PlayedGame $played){
        $played->winner_id = $winnerId;
        $played->save();
        return $played;
    }

    public function delete(PlayedGame $played)
    {
        $played->expansions()->detach();
        return $played->delete();
    }

    public function addExpansions(Array $data, PlayedGame $played)
    {
            $played->expansions()->sync($data);
            return $played;
    }
}
