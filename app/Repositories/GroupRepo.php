<?php

namespace App\Repositories;

use App\Models\Group;
use App\Repositories\Contracts\IGroup;

class GroupRepo extends Repository implements IGroup
{
    public function getGroup($id)
    {
        return Group::with(['groupUsers', 'admin', 'groupGames', 'baseGroupGames'])->find($id);
    }

    public function getGroupsOfUser($userId, $itemsPerPage = 0)
    {
        return Group::whereHas('groupUsers', function ($query) use ($userId)
                    {
                        $query->where('user_id', '=', $userId);
                    })
                    ->orWhere('admin_id', $userId)
                    ->with(['admin'])
                    ->get();
    }
    /***************************************************************************
     Next function will create or update the user object in de database
     **************************************************************************/
    public function create(Array $data, $userId = "")
    {
        $group = Group::create($data);
        $group->admin_id = $userId;
        $group->save();
        return $group;
    }

    public function update(Array $data, Group $group)
    {
        $group->update($data);
        return $group;
    }

    public function delete(Group $group)
    {
        return $group->delete();
    }
}
