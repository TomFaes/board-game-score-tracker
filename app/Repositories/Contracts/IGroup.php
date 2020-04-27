<?php

namespace App\Repositories\Contracts;
use App\Models\Group;

interface IGroup {
    public function getGroups($itemsPerPage = 0);
    public function getGroup($id);
    public function getUserGroups($userId, $itemsPerPage = 0);
    public function getPlayedGames($id);

    public function create(Array $data);
    public function update(Array $data, $groupId);
    public function delete($groupId);
}
