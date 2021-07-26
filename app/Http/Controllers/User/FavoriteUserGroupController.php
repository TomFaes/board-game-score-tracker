<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\Contracts\IUser;

use App\Http\Resources\UserResource;

use Auth;

class FavoriteUserGroupController extends Controller
{
     /** @var App\Repositories\Contracts\IUser */
     protected $user;

     public function __construct(Iuser $user) {
         $this->middleware('auth')->only('view');
         $this->middleware('auth:api');

         $this->user = $user;
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($groupId)
    {
        $userId = auth()->user()->id;
        $user = $this->user->changeFavoriteGroup($userId, $groupId);
        return response()->json(new UserResource($user), 200);
    }
}
