<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'description' => $this->description,
            'admin_id' => $this->admin_id,
            'admin' => new UserPublicResource($this->admin),
            'group_users' => $this->groupUsers,
            'group_games' => $this->groupGames,
            'base_group_games' => $this->baseGroupGames,
            'type_member' => $this->typeMember,
        ];
    }
}
