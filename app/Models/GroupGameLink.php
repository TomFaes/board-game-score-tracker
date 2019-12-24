<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupGameLink extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'link', 'description'
    ];

    public function groupGame()
    {
        return $this->belongsTo('App\Models\GroupGame', 'group_game_id', 'id')->with('game', 'group');
    }
}
