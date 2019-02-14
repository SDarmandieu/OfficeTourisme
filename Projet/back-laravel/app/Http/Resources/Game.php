<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Game extends JsonResource
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
            'desc' => $this->desc,
            'age' => $this->age,
            'city_id' => $this->city_id,
            'points' => $this->points->pluck('id'),
            'questions' => $this->questions->pluck('id'),
            'length' => $this->questions->count(),
            'files' => $this->files->pluck('id'),
            'published' => $this->published
        ];
    }
}
