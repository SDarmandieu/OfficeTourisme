<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class File extends JsonResource
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
            'filename' => $this->filename,
            'path' => $this->path,
            'type' => $this->type,
            'extension' => $this->extension,
            'alt' => $this->alt,
            'imagetype_id' => $this->imagetype_id
        ];
    }
}
