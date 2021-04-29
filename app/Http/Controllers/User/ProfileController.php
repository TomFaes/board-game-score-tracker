<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\Contracts\IUser;
use App\Validators\UserValidation;

use Auth;

class ProfileController extends Controller
{
     /** @var App\Repositories\Contracts\IUser */
     protected $user;

     public function __construct(UserValidation $userValidation, Iuser $user) {
         $this->middleware('auth')->only('view');
         $this->middleware('auth:api')->except('view');

         $this->userValidation = $userValidation;
         $this->user = $user;
     }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->user->getUser(auth()->user()->id), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $userId = auth()->user()->id;
        $this->userValidation->validateUser($request, $userId);
        $user = $this->user->update($request->all(), $userId);
        return response()->json($user, 201);
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
        $this->user->forgetUser($userId);
        return response()->json("Profile is deleted", 204);
    }
}
