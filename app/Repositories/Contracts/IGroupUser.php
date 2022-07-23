<?php

namespace App\Repositories\Contracts;

use App\Models\GroupUser;

interface IGroupUser {
    public function getGroupUser($id);
    public function getGamesCreatedByGroupUser($groupUserId);

    public function create(Array $data);
    public function update(Array $data, GroupUser $groupUser);
    public function delete(GroupUser $groupUser);

    public function createCode();
    public function joinGroup($code, $userId);
    public function regenerateGroupUserCode(GroupUser $groupUser);
}
