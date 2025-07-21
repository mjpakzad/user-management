<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserViewedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->resource->id,
            'mobile'      => $this->resource->mobile,
            'avatar'      => $this->resource->avatar
                ? asset("storage/{$this->resource->avatar}")
                : null,
            'total_views' => (int) $this->resource->total_views,
        ];
    }
}
