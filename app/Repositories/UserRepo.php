<?php

namespace App\Repositories;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepo extends Repository implements Contracts\IUser
{
    public function getAllUsers($itemsPerPage = 10)
    {
        return User::OrderBy('firstname', 'asc', 'name', 'asc')->paginate($itemsPerPage);
    }

    public function getUser($id)
    {
        return User::find($id);
    }

    /**
     * check if a user exist when he logs in with a socialite account
     */
    public function existingUser(string $socialUserEmail)
    {
        return User::where('email', $socialUserEmail)->first();
    }

    /***************************************************************************
     Next function will create or update the user object in de database
     **************************************************************************/

    protected function setUser(User $user, array $data)
    {
        isset($data['firstname']) === true ? $user->firstname = $data['firstname'] : "";
        isset($data['name']) === true ? $user->name = $data['name'] : "";
        isset($data['email']) === true ? $user->email = $data['email'] : "";

        if(isset($data['role']) === true)
        {
            $user->role = $data['role'];
        }
        if(isset($data['password']) === true)
        {
            $user->password = bcrypt($data['password'] );
        }
        return $user;
    }

    /**
     * create a user
     */
    public function create(Array $data){
        $user = new User();
        //default role is User
        $user->role = 'User';
        $user->password = Hash::make(Str::random(16));
        $user = $this->setUser($user, $data);
        $user->save();
        return $user;
    }

    /**
     * Creates a user who has been logged in trough Socialite(google, facebook, ....)
     */
    public function createSocialUser($socialUser)
    {
        $newUser = new User();
        $newUser->firstname = $socialUser['given_name'];
        $newUser->name = $socialUser['family_name'];
        $newUser->email = $socialUser['email'];
        $newUser->password = Hash::make(Str::random(16));
        $newUser->role = 'User';
        $newUser->save();
        return $newUser;
    }

    /**
     * update a user
     */
    public function update(Array $data, $userId)
    {
        $user = $this->getUser($userId);
        $user = $this->setUser($user, $data);
        $user->save();
        return $user;
    }

    /**
     * instead of deleting a user, we will randomize the data
     */
    public function forgetUser($userId){
        $user = $this->getUser($userId);
        $user->firstname = Str::random(10);
        $user->name = Str::random(10);
        $user->email = Str::random(10)."@".Str::random(10).".".Str::random(10);
        $user->forget_user = 1;
        $user->save();
        return $user;
    }

    /**
     * method is not used for now
     */
    public function delete($userId){
        $user = $this->getUser($userId);
        return $user->delete();
    }
}
