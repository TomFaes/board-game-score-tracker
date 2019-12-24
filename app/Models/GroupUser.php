<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupUser extends Model
{
    use SoftDeletes;

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
}
