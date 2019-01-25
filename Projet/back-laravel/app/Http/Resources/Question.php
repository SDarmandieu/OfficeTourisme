<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Question extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'expe' => $this->expe,
            'point_id' => $this->point_id,
            'game_id' => $this->game_id,
            'file_id' => $this->file_id,
            'done' => false
        ];
    }
}
