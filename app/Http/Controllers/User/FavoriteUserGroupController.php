<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Repositories\Contracts\IUser;
use App\Http\Resources\UserResource;
use App\Models\Group;

class FavoriteUserGroupController extends Controller
{
     protected $userRepo;

     public function __construct(Iuser $user) {
         $this->userRepo = $user;
     }

    public function update(Group $group)
    {
        $userId = auth()->user()->id;
        $user = $this->userRepo->changeFavoriteGroup($userId, $group->id);
        return response()->json(new UserResource($user), 200);
    }
}
