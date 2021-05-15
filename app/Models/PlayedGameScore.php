<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlayedGameScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'played_game_id', 'group_user_id', 'score', 'place', ' remarks', 'creator_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function playedGame()
    {
        return $this->belongsTo(PlayedGame::class)->withDefault();
    }

    public function groupUser()
    {
        return $this->belongsTo(GroupUser::class)->withDefault();
    }

}
