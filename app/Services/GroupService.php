<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\IGroupUser;
use App\Repositories\Contracts\IUser;


class GroupService
{
    protected $user;
    protected $groupUser;

    public function __construct(IUser $user, IGroupUser $groupUser)
    {
        $this->user = $user;
        $this->groupUser = $groupUser;
    }

    /**
     * this function will check if the new user is already added to some existing groups
     */
    public function checkNewUser(User $user){
        if(isset($user->id) === true){
            return true;
        }
        return false;
    }

    /**
     * check if a users email is used in the group users
     */
    public function checkExistingUser($email)
    {
        $user = $this->user->existingUser($email);

        if(isset($user->id) === true){
            return true;
        }
        return false;
    }
}
