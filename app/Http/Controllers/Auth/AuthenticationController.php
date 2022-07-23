<?php

namespace App\Http\Controllers\Auth;
/*
use App\Http\Controllers\Controller;


use Socialite;
//use Auth;
use App\Repositories\Contracts\IUser;
*/

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Socialite;

use App\Repositories\Contracts\IUser;
use App\Services\GroupService;

class AuthenticationController extends BaseController
{
   protected $userRepo;

   public function __construct(IUser $user)
   {
       $this->userRepo = $user;
   }

   /**
    * Redirect to the social providers login page
    */
    public function getSocialRedirect( $account ){
        try{
            return Socialite::with( $account )->redirect();
        }catch ( \InvalidArgumentException $e ){
            return redirect('/');
        }
    }

    /**
     * Handles the authentication
     */
    public function getSocialCallback( $account ){
        try {
            //get account info from the user who logged in
            $socialUser = Socialite::with( $account )->user();
            //check if the user exist
            $user = $this->userRepo->existingUser($socialUser->email);
            //if user doesn't exist create one
            if($user == null){
                $user = $this->userRepo->createSocialUser($socialUser);

            }
            //if the user exist or is created login
            Auth::login($user, true);
            Auth::user()->createToken('boardgame')->accessToken;

            return redirect('/');
        }
        catch (Exception $e) {
            return 'error';
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
