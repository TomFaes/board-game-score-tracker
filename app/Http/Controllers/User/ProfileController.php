<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\Contracts\IUser;

use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use Auth;

class ProfileController extends Controller
{
     protected $userRepo;

     public function __construct(Iuser $user) {
         $this->userRepo = $user;
     }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(new UserResource($this->userRepo->getUser(auth()->user()->id)), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequest $request)
    {
        $userId = auth()->user()->id;
        $user = $this->userRepo->update($request->all(), $userId);
        return response()->json(new UserResource($user), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $userId = Auth::user()->id;
        $this->userRepo->forgetUser($userId);
        return response()->json("Profile is deleted", 204);
    }
}
