<?php

namespace App\Repositories;

use App\Models\Game;
use App\Repositories\Contracts\IGame;
use Illuminate\Support\Facades\DB;

class GameRepo extends Repository implements IGame
{
    public function getGame($id)
    {
        return Game::with(['expansions', 'baseGame'])->find($id);
    }

    public function getGames($itemsPerPage = 0)
    {
        if ($itemsPerPage > 0) {
            return Game::with(['expansions', 'baseGame', 'groupGames'])->OrderBy('full_name', 'asc')->paginate($itemsPerPage);
        }
        return Game::with(['expansions', 'baseGame', 'groupGames'])->OrderBy('full_name', 'asc')->get();
    }

    /**
     * Get all games which aren't an expansion
     */
    public function getBaseGames($itemsPerPage = 0)
    {
        if ($itemsPerPage > 0){
            return Game::with(['expansions', 'baseGame'])->OrderBy('name', 'asc')->where('base_game_id', null)->where('approved_by_admin', 1)->paginate($itemsPerPage);
        }
        return Game::with(['expansions', 'baseGame'])->OrderBy('name', 'asc')->where('base_game_id', null)->where('approved_by_admin', 1)->get();
    }

    public function getExpansionGames($gameId, $itemsPerPage = 0)
    {
        if ($itemsPerPage > 0){
            return Game::with(['expansions', 'baseGame'])->where('base_game_id', $gameId)->where('approved_by_admin', 1)->paginate($itemsPerPage);
        }
        return Game::with(['expansions', 'baseGame'])->where('base_game_id', $gameId)->where('approved_by_admin', 1)->get();
    }

    public function getUnapprovedGames($itemsPerPage = 0)
    {
        if ($itemsPerPage > 0){
            return Game::with(['expansions', 'baseGame'])->where('approved_by_admin', 0)->OrderBy('name', 'asc')->paginate($itemsPerPage);
        }
        return Game::with(['expansions', 'baseGame'])->where('approved_by_admin', 0)->OrderBy('name', 'asc')->get();
    }

    /**
     * Get all games that are not selected in a group.
     */
    public function searchGamesNotInGroup($arrayGameIds = "")
    {
        return Game::with(['baseGame'])->whereNotIn('id', $arrayGameIds)->orderBy('full_name')->get();
    }

    /***************************************************************************
     Next function will create or update the user object in de database
     **************************************************************************/
    protected function setFullName(Game $game)
    {
        $game->full_name = $game->name;
        if($game->base_game_id > 0){
            $game->full_name = $game->baseGame->name.": ".$game->name;
        }
        return $game;
    }

     public function create(array $data){
         $game = Game::create($data);
         $game = $this->setFullName($game);
         $game->save();
         return $game;
     }

    public function approveGame(Game $game)
    {
        $game->approved_by_admin = 1;
        $game->save();
        return $game;
    }

    public function update(Array $data, Game $game)
    {
        $game->update($data);
        $game = $this->setFullName($game);
        $game->save();

        //check if there are expansions and update the full name => move to observerclass???
        if($game->base_game_id == Null){
            foreach($game->expansions AS $expansion){
                $expansion = $this->setFullName($expansion);
                $expansion->save();
            }
        }
        return $game;
    }

    public function updateBaseGameId($gameId, $newGameId)
    {
       return Game::where('base_game_id' , '=', $gameId)->update(
            [
                'base_game_id' => $newGameId
            ]
        );
    }

    public function updateExpansion($id, $newGameId)
    {
        return DB::statement('update expansion_played_game SET game_id ='.$newGameId. ' WHERE game_id = '.$id);
        return 'expansions updated';
    }

    public function delete($game)
    {
        return $game->delete();
    }
}
