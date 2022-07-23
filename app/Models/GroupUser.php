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
        'firstname', 'name', 'group_id', 'user_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class)->select(['id', 'name', 'admin_id', 'description'])->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select(['id', 'firstname', 'name'])->withDefault();
    }

    public function gameCreator()
    {
        return $this->hasMany(PlayedGame::class, 'creator_id', 'user_id');
    }

    protected $appends = ['fullName'];

    /**
     * Returns a name for dropdowns
     */
    public function getfullNameAttribute()
    {
        if($this->user->id > 0){
            return $this->user->firstname." ".$this->user->name;
        }
        return $this->firstname." ".$this->name;
    }
}
