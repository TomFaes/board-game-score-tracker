<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
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
            'name' => $this->name,
            'year' => $this->year,
            'players_min' => (string)$this->players_min,
            'players_max' => $this->players_max,
            'full_name' => $this->full_name,
            'approved_by_admin' => $this->approved_by_admin,
            //'base_game' => $this->baseGame,
            //'expansions' => $this->whenLoaded('expansions'),
            'base_game' => new GameResource($this->baseGame),
            'expansions' => GameResource::collection($this->whenLoaded('expansions')),
        ];
        //return parent::toArray($request);
    }
}
