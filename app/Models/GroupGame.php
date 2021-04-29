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
        return $this->belongsTo('App\Models\Game', 'game_id', 'id')->select(['id', 'name', 'full_name', 'players_max', 'players_min', 'base_game_id'])->withDefault();
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'group_id', 'id')->withDefault();
    }

    public function links()
    {
        return $this->hasMany('App\Models\GroupGameLink', 'group_game_id', 'id');
    }


}
