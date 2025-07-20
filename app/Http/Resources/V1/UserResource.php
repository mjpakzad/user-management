<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->resource->id,
            'mobile' => $this->resource->mobile,
            'avatar' => $this->resource->avatar ? asset("storage/{$this->resource->avatar}") : null,
            'created_at' => $this->resource->created_at,
        ];
    }
}
