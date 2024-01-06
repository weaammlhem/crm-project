<?php

namespace App\Http\Resources;

use App\Models\Stage;
use App\Models\Task;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Task
 */
class TaskResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'stage' => new StageResource($this->stage),
            'users' =>  UserResource::collection($this->users),
        ];
    }

}
