<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class PostFileResource extends JsonResource
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
            'file_link' => config('app.url') . '/api/posts/files/' . $this->resource->path,
            'key' => $this->resource->key,
            'post_id' => $this->resource->post_id
        ];
    }
}
