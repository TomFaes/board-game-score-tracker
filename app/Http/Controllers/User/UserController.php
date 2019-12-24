<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\Contracts\IUser;
use App\Services\GroupService;
use App\Validators\UserValidation;

class UserController extends Controller
{

    /** @var App\Repositories\Contracts\IUser */
    protected $user;

    public function __construct(UserValidation $userValidation, IUser $user) {
        $this->middleware('auth')->only('view');
        $this->middleware('auth:api')->except('view');

        $this->middleware('admin:Admin, View',['only' => ['view']]);
        $this->middleware('admin:Admin',['except' => ['view']]);

        $this->userValidation = $userValidation;
        $this->user = $user;
    }

     /**
     * default view
     *
     * @return \Illuminate\Http\Response
     */
    public function view()
    {
        return view('user.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->user->getAllUsers(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->userValidation->validateUser($request);
        $user = $this->user->create($request->all());

        //Check if the user is already added to a group
        $groupService = resolve(GroupService::class);
        $groupService->checkNewUser($user);

        return response()->json($user, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($this->user->getUser($id), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->userValidation->validateUser($request, $id);
        $user = $this->user->update($request->all(), $id);
        return response()->json($user, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->user->forgetUser($id);
        return response()->json("User is deleted", 204);
    }
}
