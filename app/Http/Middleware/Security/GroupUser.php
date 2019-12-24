<?php

namespace App\Http\Middleware\Security;
use Closure;

class GroupUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $method = 'API')
    {
        $message = "Group user middleware: You do not have the right to change this group";
        $user = auth()->user();

        $group = app('App\Repositories\Contracts\IGroup');
        $group = $group->getGroup($request->route('group_id'));

        $groupUser = app('App\Repositories\Contracts\IGroupUser');
        $groupUser = $groupUser->getGroupUser($request->route('id'));


        if(isset($group) === true){
            //check if  logged in user is the admin of the group
            if($group->admin_id == $user->id){
                //Request method: DELETE
                if($request->method() == "DELETE"){
                    if($groupUser->user_id != $group->admin_id){
                        return $next($request);
                    }else{
                        $message = "Group user middleware: The admin cannot be removed from the group";
                    }
                }
                //Request method: other
                return $next($request);
            }
        }
        if($method == 'API'){
            return response()->json($message, 200);
        }else{
            return redirect('/')->with('error', $message);
        }
    }
}
