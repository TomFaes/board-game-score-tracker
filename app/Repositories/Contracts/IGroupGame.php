<?php

namespace App\Repositories\Contracts;

interface IGroupGame {
    public function getGroupGames();
    public function getGroupGame($id);
    public function getGroupGameIds($id);
    public function getGamesOfGroup($groupId, $itemsPerPage = 0);


    public function create(Array $data);
    public function delete($Id);
}
