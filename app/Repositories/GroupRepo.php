<?php

namespace App\Repositories;

use App\Models\Group;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\IGroup;
use Auth;

class GroupRepo extends Repository implements Contracts\IGroup
{
    /**
     * Get all the group
     * @return Object
     */
    public function getGroups($itemsPerPage = 0)
    {
        if($itemsPerPage > 0){
            return Group::with(['groupUsers', 'admin'])->OrderBy('name', 'asc')->paginate($itemsPerPage);
        }
        return Group::with(['groupUsers', 'admin', 'groupGames'])->OrderBy('name', 'asc')->get();
    }

    /**
     * Get a group
     * @return Object
     */
    public function getGroup($id)
    {
        return Group::with(['groupUsers', 'admin', 'groupGames', 'baseGroupGames'])->find($id);
    }

     /**
     * Get all groups of a user where he is either admin or member
     * @return Object
     */
    public function getUserGroups($userId, $itemsPerPage = 0)
    {
        if($itemsPerPage > 0){
            return Group::whereHas('groupUsers', function ($query) use ($userId){
                $query->where('user_id', '=', $userId)->where('verified', 1);
            })->orWhere('admin_id', $userId)->with(['groupUsers','admin', 'groupGames', 'baseGroupGames'])->paginate($itemsPerPage);
        }
        return Group::whereHas('groupUsers', function ($query) use ($userId){
            $query->where('user_id', '=', $userId)->where('verified', 1);
        })->orWhere('admin_id', $userId)->with(['groupUsers','admin', 'groupGames', 'baseGroupGames'])->get();
    }

     /**
     * get all played games in this group
     * @return Object
     */
    public function getPlayedGames($id){
        return Group::with('playedGames')->find($id);
    }

    /***************************************************************************
     Next function will create or update the user object in de database
     **************************************************************************/

     /**
     * set the data of a group
     * @return Object
     */
    protected function setGroup(Group $group, array $data)
    {
        isset($data['name']) === true ? $group->name = $data['name'] : "";
        isset($data['description']) === true ? $group->description = $data['description'] : "";
        isset($data['admin_id']) === true ? $group->admin_id = $data['admin_id'] : "";
        return $group;
    }

    /**
     * Create a new group
     * @return Object
     */
    public function create(Array $data, $userId = "")
    {
        $group = new Group();
        $group = $this->setGroup($group, $data);
        $group->admin_id = $userId;
        $group->save();
        return $group;
    }

    /**
     * Update a group
     * @return Object
     */
    public function update(Array $data, $groupId)
    {
        $group = $this->getGroup($groupId);
        $group = $this->setGroup($group, $data);
        $group->save();
        return $group;
    }

    /**
     * Delete a group
     * @return Object
     */
    public function delete($groupId)
    {
        $group = $this->getGroup($groupId);
        return $group->delete();
    }
}
