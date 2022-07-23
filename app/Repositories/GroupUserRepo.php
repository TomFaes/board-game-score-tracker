<?php

namespace App\Repositories;

use App\Models\GroupUser;

use App\Repositories\Contracts\IGroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GroupUserRepo extends Repository implements IGroupUser
{
    public function getGroupUsers($groupId, $itemsPerPage = 0)
    {
        if($itemsPerPage > 0){
            return GroupUser::where('group_id', $groupId)->with(['group', 'user'])->paginate($itemsPerPage);
        }
        return GroupUser::with(['group', 'user'])->where('group_id', $groupId)->get();
    }

    public function getGroupUser($id)
    {
        return GroupUser::with(['group', 'user'])->find($id);
    }

    //get a groupuser with all the games he has created.
    public function getGamesCreatedByGroupUser($groupUserId)
    {
        return GroupUser::with('gameCreator')->find($groupUserId);
    }

     /***************************************************************************
     Next function will create or update the user object in de database
     **************************************************************************/
    public function create(Array $data)
    {
        $groupUser = GroupUser::create($data);
        $groupUser->code = $this->createCode();
        $groupUser->save();
        return $groupUser;
    }

    public function update(Array $data, GroupUser $groupUser){
        $groupUser->update($data);
        return $groupUser;
    }

    public function delete(GroupUser $groupUser)
    {
        return $groupUser->delete();
    }

    /**
     * create a unique code for a user to join a group
     */
    public function createCode()
    {
        $createCodeString = "";
        for($x=0; $x < 5; $x++){
            $createCodeString = microtime().Str::random(20);
            $createCodeString = Hash::make($createCodeString);

            $checkIfCodeExist = GroupUser::where('code', $createCodeString)->first();
            if($checkIfCodeExist == null){
                return $createCodeString;
            }
        }
        return "Failed to create a unique code";
    }

    public function joinGroup($code, $userId)
    {
        if(strlen($code) <= 0){
            return false;
        }
        $groupUser = GroupUser::where('code', $code)->first();

        if($groupUser == null){
            return false;
        }
        $groupUser->code = Null;
        $groupUser->user_id = $userId;
        $groupUser->save();
        return $groupUser;
    }

    public function regenerateGroupUserCode(GroupUser $groupUser){
        if($groupUser->user_id != null){
            return "There is still a user connected to this group user";
        }

        $groupUser->code = $this->createCode();
        $groupUser->save();
        return $groupUser;
    }
}
