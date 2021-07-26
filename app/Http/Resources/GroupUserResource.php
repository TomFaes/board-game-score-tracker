<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupUserResource extends JsonResource
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
            'firstname' => $this->firstname,
            'name' => $this->name,
            'fullname' => $this->firstname." ".$this->name,
            'code' => $this->code,
            'group_id' => $this->group_id,
            'group' => $this->group,
            'user_id' => $this->user_id,
            'user' => new UserPublicResource($this->user),
            'links' => $this->links
        ];
    }
}
