<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->resource->name,
            'surname' => $this->resource->surname,
            'email' => $this->resource->email,
            'password' => $this->resource->password,
            'profile_image' => $this->resource->profile_image,
            'followers_count' => $this->resource->followers_count,
            'following_count' => $this->resource->following_count,
            'posts' => PublicPostResource::collection($this->whenLoaded('posts')),
        ];
    }
}
