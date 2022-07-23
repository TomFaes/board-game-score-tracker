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
        'name', 'link', 'description', 'group_game_id'
    ];

    public function groupGame()
    {
        return $this->belongsTo(GroupGame::class)->with('game', 'group');
    }
}
