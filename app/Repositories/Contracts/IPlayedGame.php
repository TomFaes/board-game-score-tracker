<?php

namespace App\Repositories\Contracts;

use App\Models\PlayedGame;

interface IPlayedGame {
    public function getPlayedGames();
    public function getPlayedGame($id);
    public function getPlayedGroupGames($groupId);

    public function getStatPlayedGroupGames($groupId);
    public function getStatPlayedGroupYearGames($groupId);
    public function getStatPlayedGame($groupId, $gameId);

    public function create(Array $data);
    public function update(Array $data, PlayedGame $played);
    public function updateGameIds($gameId, $newGameId);
    public function setWinner($winnerId, PlayedGame $played);
    public function delete(PlayedGame $played);

    public function addExpansions(Array $data, PlayedGame $played);
}
