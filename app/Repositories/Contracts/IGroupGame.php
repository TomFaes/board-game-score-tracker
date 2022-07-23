<?php

namespace App\Repositories\Contracts;

use App\Models\GroupGame;

interface IGroupGame {
    public function getGroupGame($id);
    public function getIdsOfGamesOfGroup($id);
    public function getGamesOfGroup($groupId, $itemsPerPage = 0);

    public function create(Array $data);
    public function updateGroupGameIds($gameId, $newGameId);
    public function delete(GroupGame $game);
}
