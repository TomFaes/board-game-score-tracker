<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupGameLink extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name', 'link', 'description'
    ];

    public function groupGame()
    {
        return $this->belongsTo('App\Models\GroupGame', 'group_game_id', 'id')->with('game', 'group');
    }
}
