<?php

namespace App\Repositories;

use App\Models\GroupGameLink;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\IGroup;
use Auth;

class GroupGameLinkRepo extends Repository implements Contracts\IGroupGameLink
{
    /**
     * Get all the group game links
     * @return Object
     */
    public function getGroupGameLinks($itemsPerPage = 0)
    {
        if($itemsPerPage > 0){
            return GroupGameLink::with(['groupGame'])->paginate($itemsPerPage);
        }
        return GroupGameLink::with(['groupGame'])->get();
    }

    /**
     * Get a group game link
     * @return Object
     */
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

     /**
     * set the data of an object
     * @return Object
     */
    protected function setGroupGameLink(GroupGameLink $groupGameLink, array $data)
    {
        isset($data['group_game_id']) === true ? $groupGameLink->group_game_id = $data['group_game_id'] : "";
        isset($data['name']) === true ? $groupGameLink->name = $data['name'] : "";
        isset($data['link']) === true ? $groupGameLink->link = $data['link'] : "";
        $groupGameLink->description = $data['description'] ?? "";
        //$groupGameLink->description = isset($data['description']) === true ? $data['description'] : "";
        //isset($data['description']) === true ? $groupGameLink->description = $data['description'] : "";
        return $groupGameLink;
    }

    /**
     * Create a new object
     * @return Object
     */
    public function create(Array $data)
    {
        $groupGameLink = new GroupGameLink();
        $groupGameLink = $this->setGroupGameLink($groupGameLink, $data);
        $groupGameLink->save();
        return $groupGameLink;
    }

    /**
     * Update a object
     * @return Object
     */
    public function update(Array $data, $id)
    {
        $groupGameLink = $this->getGroupGameLink($id);
        $groupGameLink = $this->setGroupGameLink($groupGameLink, $data);
        $groupGameLink->save();
        return $groupGameLink;
    }

    /**
     * Delete a object
     * @return Object
     */
    public function delete($id)
    {
        $groupGameLink = $this->getGroupGameLink($id);
        return $groupGameLink->delete();
    }
}
