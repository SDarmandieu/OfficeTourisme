<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\File;

class Answer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $file = File::find($this->file_id);
        return [
            'id' => $this->id,
            'content' => $this->content,
            'valid' => $this->valid,
            'question_id' => $this->question_id,
            'file' => $file
        ];
    }
}
