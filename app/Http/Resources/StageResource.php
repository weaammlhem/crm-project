<?php

namespace App\Http\Resources;

use App\Models\Stage;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Stage
 */
class StageResource  extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'project' => new ProjectResource($this->project),
            'name' => $this->name,
            'color' => $this->color,
        ];
    }

}
