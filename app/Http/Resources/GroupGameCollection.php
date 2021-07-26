<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GroupGameCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' =>  GroupGameResource::collection($this->collection),
            'test' => function_exists('currentPage'),

            /*
            'current_page' => $this->currentPage() ?? null,
            'last_page' => $this->lastPage() ?? null,
            'to' => $this->perPage() ?? null,
            */
        ];
    }
}
