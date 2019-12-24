<?php

namespace App\Repositories\Contracts;
use App\Models\Game;

interface IGame {
    public function getGames($itemsPerPage = 0);
    public function getBaseGames($itemsPerPage = 0);
    public function getGame($id);
    public function getExpansionGames($gameId);
    public function getApprovedGames($itemsPerPage = 0);
    public function getUnapprovedGames($itemsPerPage = 0);

    public function create(Array $data);
    public function approveGame(Game $game);
    public function update(Array $data, $gameId);
    public function delete($gameId);

    public function searchGamesNotInGroup($arrayGameIds = "", $searchString = "");
}
