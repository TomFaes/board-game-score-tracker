<?php

namespace App\Repositories;

use App\Models\Game;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\IGame;
use Auth;

class GameRepo extends Repository implements Contracts\IGame
{
    /**
     * Get all the games
     * @return Object
     */
    public function getGames($itemsPerPage = 0)
    {
        if($itemsPerPage > 0){
             return Game::with(['expansions', 'baseGame', 'groupGames'])->OrderBy('name', 'asc')->paginate($itemsPerPage);
        }
        return Game::with(['expansions', 'baseGame', 'groupGames'])->OrderBy('name', 'asc')->get();
    }

    /**
     * Get all the base games
     * @return Object
     */
    public function getBaseGames($itemsPerPage = 0)
    {
        if($itemsPerPage > 0){
            return Game::with(['expansions', 'baseGame'])->OrderBy('name', 'asc')->where('base_game_id', null)->where('approved_by_admin', 1)->paginate($itemsPerPage);
        }
        return Game::with(['expansions', 'baseGame'])->OrderBy('name', 'asc')->where('base_game_id', null)->where('approved_by_admin', 1)->get();
    }

    /**
     * Get a game
     * @return Object
     */
    public function getGame($id)
    {
        return Game::with(['expansions', 'baseGame'])->find($id);
    }

    /**
     * Get all the expansion games
     * @return Object
     */
    public function getExpansionGames($gameId, $itemsPerPage = 0)
    {
        if($itemsPerPage > 0){
            return Game::with(['expansions', 'baseGame'])->where('base_game_id', $gameId)->where('approved_by_admin', 1)->paginate($itemsPerPage);
        }
        return Game::with(['expansions', 'baseGame'])->where('base_game_id', $gameId)->where('approved_by_admin', 1)->get();
    }

    /**
     * Get all the approved games
     * @return Object
     */
    public function getApprovedGames($itemsPerPage = 0){
        if($itemsPerPage > 0){
            return Game::with(['expansions', 'baseGame'])->where('approved_by_admin', 1)->OrderBy('name', 'asc')->paginate($itemsPerPage);
       }
       return Game::with(['expansions', 'baseGame'])->where('approved_by_admin', 1)->OrderBy('name', 'asc')->get();
    }

    /**
     * Get all the unapproved games
     * @return Object
     */
    public function getUnapprovedGames($itemsPerPage = 0){
        if($itemsPerPage > 0){
            return Game::with(['expansions', 'baseGame'])->where('approved_by_admin', 0)->OrderBy('name', 'asc')->paginate($itemsPerPage);
       }
       return Game::with(['expansions', 'baseGame'])->where('approved_by_admin', 0)->OrderBy('name', 'asc')->get();
    }

    /**
     * Get all games that are not in the array of games
     * @return Object
     */
    public function searchGamesNotInGroup($arrayGameIds = "", $searchString = "")
    {
        return Game::whereNotIn('id', $arrayGameIds)
            ->where('name', 'like', "%$searchString%")
            ->get();
    }

    /***************************************************************************
     Next function will create or update the user object in de database
     **************************************************************************/

     /**
     * set the data of a game
     * @return Object
     */
    protected function setGame(Game $game, array $data)
    {
        isset($data['name']) === true ? $game->name = $data['name'] : "";
        isset($data['year']) === true ? $game->year = $data['year'] : "";
        isset($data['players_min']) === true ? $game->players_min = $data['players_min'] : "";
        isset($data['players_max']) === true ? $game->players_max = $data['players_max'] : "";
        isset($data['base_game_id']) === true ? $game->base_game_id = $data['base_game_id'] : "";
        return $game;
    }

    /**
     * Create a new game
     * @return Object
     */
    public function create(Array $data)
    {
        $game = new Game();
        $game = $this->setGame($game, $data);
        $game->save();
        return $game;
    }

    /**
     * Approve a game
     * @return Object
     */
    public function approveGame(Game $game)
    {
        $game->approved_by_admin = 1;
        $game->save();
        return $game;
    }

    /**
     * Update a game
     * @return Object
     */
    public function update(Array $data, $gameId)
    {
        $game = $this->getGame($gameId);
        $game = $this->setGame($game, $data);
        $game->save();
        return $game;
    }

    /**
     * Delete a game
     * @return Object
     */
    public function delete($gameId)
    {
        $game = $this->getGame($gameId);
        return $game->delete();
    }
}
