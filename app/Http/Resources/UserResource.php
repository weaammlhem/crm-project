<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'team' => new TeamResource($this->team),
            'address' => $this->address,
            'age' => $this->age,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'specialize' => $this->specialize,
            'type' => $this->type,
        ];
    }

}
