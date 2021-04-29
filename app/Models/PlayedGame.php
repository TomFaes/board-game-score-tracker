<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlayedGame extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'group_id', 'game_id', 'winner_id', 'date', 'time_played', ' remarks', 'creator_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'group_id', 'id')->select(['id', 'name'])->withDefault();
    }

    public function winner()
    {
        return $this->belongsTo('App\Models\GroupUser', 'winner_id', 'id')->withDefault();
    }

    public function game()
    {
        return $this->belongsTo('App\Models\Game', 'game_id', 'id')->with('expansions')->withDefault();
    }

    public function creator(){
        return $this->belongsTo('App\Models\User', 'creator_id', 'id')->select(['id', 'firstname', 'name'])->withDefault();
    }

    //Pivot table connection
     public function expansions(){
        return $this->belongsToMany('App\Models\Game', 'expansion_played_game');
    }

    public function scores()
    {
        return $this->hasMany('App\Models\PlayedGameScore', 'played_game_id', 'id');
    }

}
