<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'heading' => $this->resource->heading,
            'content' => $this->resource->content,
            'view_count' => $this->resource->view_count,
            'author' => [
                'id' => $this->resource->user->id,
                'mobile' => $this->resource->user->mobile,
                'avatar' => $this->resource->user->avatar
                    ? asset("storage/{$this->resource->user->avatar}")
                    : null,
            ],
        ];
    }
}
