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
        'group_id', 'game_id', 'date', 'time_played', 'remarks'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class)->select(['id', 'name'])->withDefault();
    }

    public function winner()
    {
        return $this->belongsTo(GroupUser::class, 'winner_id', 'id')->withDefault();
    }

    public function game()
    {
        return $this->belongsTo(Game::class)->with('expansions')->withDefault();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id')->select(['id', 'firstname', 'name'])->withDefault();
    }

    //Pivot table connection
     public function expansions()
     {
        return $this->belongsToMany(Game::class, 'expansion_played_game');
    }

    public function scores()
    {
        return $this->hasMany(PlayedGameScore::class);
    }

}
