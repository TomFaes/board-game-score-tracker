<?php

namespace App\Http\Middleware\Security;
use Closure;
use Illuminate\Http\Request;

class GroupSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $group = $request->route('group');

        if($group->type_member === false){
            $message = "You do not have the right to the view this group:  ".$group->name;
            return response()->json($message, 401);
        }

        if($group->type_member == 'Admin'){
            return $next($request);
        }

        if($group->type_member == 'User'){
            if($request->method() != 'GET'){
                $message = "You do not have the right to change the group: ".$group->name;
                return response()->json($message, 401);
            }
            return $next($request);
        }

        //this should never happen.
        $message = "There is an undefined reason why you can't acces to the group: ".$group->name;
        return response()->json($message, 401);
    }
}
