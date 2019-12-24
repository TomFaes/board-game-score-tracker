<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'admin_id', 'description'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * Returns the base game of the expansion
     */
    public function admin()
    {
        return $this->belongsTo('App\Models\User', 'admin_id', 'id')->select(['id', 'firstname', 'name'])->withDefault();
    }

    public function groupUsers()
    {
        return $this->hasMany('App\Models\GroupUser', 'group_id', 'id')->with('user');
    }

    public function groupGames()
    {
        return $this->hasMany('App\Models\GroupGame', 'group_id', 'id')->with('game');
    }

    protected $appends = ['display' , 'typeMember'];

    /**
     * Returns a name for dropdowns
     */
    public function getDisplayAttribute()
    {
        return $this->name;
    }

    public function getTypeMemberAttribute()
    {
        if(isset(Auth::user()->id) === true){
            if($this->admin_id == Auth::user()->id){
                return "Admin";
            }
        }
        return "User";
    }
}
