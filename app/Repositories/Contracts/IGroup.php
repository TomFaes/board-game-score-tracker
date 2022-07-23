<?php

namespace App\Repositories\Contracts;

use App\Models\Group;

interface IGroup {
    public function getGroup($id);
    public function getGroupsOfUser($userId, $itemsPerPage = 0);

    public function create(Array $data);
    public function update(Array $data, Group $group);
    public function delete(Group $group);
}
