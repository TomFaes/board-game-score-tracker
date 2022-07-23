<?php

namespace App\Repositories;

use App\Models\GroupGameLink;

class GroupGameLinkRepo extends Repository implements Contracts\IGroupGameLink
{
    public function getGroupGameLink($id)
    {
        return GroupGameLink::with(['groupGame'])->find($id);
    }

    public function getLinksOfGroupGame($groupGameId)
    {
        return GroupGameLink::with(['groupGame'])->where('group_game_id', $groupGameId)->get();
    }

    /***************************************************************************
     Next function will create or update the object in de database
     **************************************************************************/
    public function create(Array $data)
    {
        $groupGameLink = groupGameLink::create($data);
        return $groupGameLink;
    }

    public function update(Array $data, GroupGameLink $link)
    {
        $link->update($data);
        return $link;
    }

    public function delete(GroupGameLink $link)
    {
        return $link->delete();
    }
}
