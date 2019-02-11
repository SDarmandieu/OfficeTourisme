<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
        $answers_to_game_id = [];
        $answers = $this->answers;
        foreach($answers as $answer)
        {
            array_push($answers_to_game_id,$answer->question->game_id);
        }

        return [
            'id' => $this->id,
            'filename' => $this->filename,
            'path' => $this->path,
            'type' => $this->type,
            'extension' => $this->extension,
            'alt' => $this->alt,
            'imagetype_id' => $this->imagetype_id,
            'games_to_id' => $this->games->pluck('id'),
            'questions_to_game_id' => $this->questions->pluck('game_id'),
            'answers_to_game_id' => $answers_to_game_id
//            'binary_content' => Storage::disk('public')->get($this->path)
        ];
    }
}
