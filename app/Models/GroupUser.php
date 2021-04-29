<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupUser extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'firstname', 'name', 'email'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * Return the group
     */
    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'group_id', 'id')->select(['id', 'name', 'admin_id', 'description'])->withDefault();
    }

     /**
     * Returns the user
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id')->select(['id', 'firstname', 'name'])->withDefault();
    }

     /**
     * Return the group
     */
    public function gameCreator()
    {
        return $this->hasMany('App\Models\PlayedGame', 'creator_id', 'user_id');
    }

    protected $appends = ['fullName'];

    /**
     * Returns a name for dropdowns
     */
    public function getfullNameAttribute()
    {
        if($this->user->id > 0 && $this->verified == 1){
            return $this->user->firstname." ".$this->user->name;
        }
        return $this->firstname." ".$this->name;
    }
}
