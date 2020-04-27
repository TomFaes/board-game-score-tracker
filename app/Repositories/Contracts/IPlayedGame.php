<?php

namespace App\Repositories\Contracts;

interface IPlayedGame {
    public function getPlayedGames();
    public function getPlayedGame($id);
    public function getPlayedGroupGames($groupId);


    public function getStatPlayedGroupGames($groupId);
    public function getStatPlayedGroupYearGames($groupId);
    public function getStatPlayedGame($groupId, $gameId);


    public function create(Array $data);
    public function update(Array $data, $id);
    public function updateGameIds($gameId, $newGameId);
    public function setWinner($winnerId, $id);
    public function delete($id);

    public function addExpansions(Array $data, $id);
}
