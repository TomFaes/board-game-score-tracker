<?php

namespace App\Repositories\Contracts;

use App\Models\GroupGameLink;

interface IGroupGameLink {
    public function getGroupGameLink($id);
    public function getLinksOfGroupGame($groupGameId);

    public function create(Array $data);
    public function update(Array $data, GroupGameLink $link);
    public function delete(GroupGameLink $link);
}
