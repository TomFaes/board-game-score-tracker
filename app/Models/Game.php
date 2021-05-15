<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use SoftDeletes;
    use HasFactory;


    protected $fillable = [
        'name' ,'players_min', 'players_max', 'base_game_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * Returns the base game of the expansion
     */
    public function baseGame()
    {
        return $this->belongsTo(Game::class, 'base_game_id', 'id');
    }

    /**
     * Returns all expansions of a game
     */
    public function expansions()
    {
        return $this->hasMany(Game::class, 'base_game_id', 'id');
    }

     /**
     * Returns all group games
     */
    public function groupGames()
    {
        return $this->hasMany(GroupGame::class);
    }

    protected $appends = ['display'];

    /**
     * Returns a name for dropdowns
     */
    public function getDisplayAttribute()
    {
        return $this->full_name;
    }

    //Pivot table connection
    public function playedGameExpansions()
    {
        return $this->belongsToMany(PlayedGame::class, 'expansion_played_game');
    }
}
