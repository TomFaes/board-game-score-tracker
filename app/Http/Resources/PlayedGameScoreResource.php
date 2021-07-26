<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayedGameScoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'played_game_id' => $this->group_id,
            'played_game' => $this->playedGame,
            'group_user_id' => $this->group_user_id,
            'group_user' => $this->groupUser,
            'score' => $this->score,
            'place' => $this->place,
            'remarks' => $this->remarks,
        ];
    }
}
