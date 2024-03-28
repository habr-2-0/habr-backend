<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'status' => $this->resource->status,
            'tags' => $this->resource->tags,
            'views' => $this->resource->views,
            'post_files' => PostFileResource::collection($this->resource->whenLoaded('post_files')), // make PostFileResource
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
