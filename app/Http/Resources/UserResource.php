<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'role' => $this->role,
            'favorite_group_id' => $this->favorite_group_id,
            'full_name' => $this->firstname." ".$this->name,
        ];
        //return parent::toArray($request);
    }
}
