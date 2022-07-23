<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use SoftDeletes;
    use HasFactory;

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
        return $this->belongsTo(User::class, 'admin_id', 'id')->withDefault();
        //return $this->belongsTo(User::class, 'admin_id', 'id')->select(['id', 'firstname', 'name'])->withDefault();
    }

    public function groupUsers()
    {
        return $this->hasMany(GroupUser::class)->with('user');
    }

    public function groupGames()
    {
        $groupgames = $this->hasMany(GroupGame::class)->with('game', 'game.expansions');
        $groupgames = $groupgames->join('games' , 'group_games.game_id', '=', 'games.id')->orderBy('games.full_name');
        return $groupgames;
    }

    public function baseGroupGames()
    {
        $groupgames =  $this->hasMany(GroupGame::class)->whereHas('game', function(Builder $query){
            $query->where('base_game_id', '=', null);
        })->with('game', 'game.expansions');

        $groupgames = $groupgames->join('games' , 'group_games.game_id', '=', 'games.id')
                                                        ->orderBy('games.full_name');
        return $groupgames;
    }

    public function playedGames()
    {
        return $this->hasMany(PlayedGame::class);
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
        $userId = Auth::user()->id;
        if($this->admin_id == $userId)
        {
            return "Admin";
        }

        foreach($this->groupUsers AS $groupUser)
        {
            if($groupUser->user_id == $userId)
            {
                return "User";
            }
        }
        return false;
    }
}
