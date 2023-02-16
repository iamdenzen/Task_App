<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Progress_listResource extends JsonResource
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
            'progress_list_id' => $this->id,
            'progress_list' => $this->progress_list,
            'author' => new UserResource($this->whenLoaded('author')),
            'task' => new TaskResource($this->whenLoaded('task')),
        ];
    }
}
