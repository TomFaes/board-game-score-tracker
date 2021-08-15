<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupGame extends Model
{
    use SoftDeletes;
    use HasFactory;

    public function game()
    {
        return $this->belongsTo(Game::class)->select(['id', 'name', 'full_name', 'players_max', 'players_min', 'base_game_id'])->with('expansions')->withDefault();
    }

    public function group()
    {
        return $this->belongsTo(Group::class)->withDefault();
    }

    public function links()
    {
        return $this->hasMany(GroupGameLink::class);
    }
}
