<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayedGameResource extends JsonResource
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
            'group_id' => $this->group_id,
            'game_id' => $this->game_id,
            'game' => $this->game,
            'winner_id' => $this->winner_id,
            'winner' => new UserPublicResource($this->winner),
            'creator_id' => $this->creator_id,
            'creator' => new UserPublicResource($this->creator),
            'date' => $this->date,
            'time_played' => $this->time_played,
            'remarks' => $this->remarks,
            'played_expansions' => $this->expansions
        ];
    }
}
