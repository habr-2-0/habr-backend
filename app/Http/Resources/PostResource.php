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
    public function toArray(Request $request):array
    {
        return[
            'id' =>$this->resource->id,
            'user_id' =>$this->resource->user_id,
            'title' =>$this->resource->title,
            'description' => $this->resource->description,
            'status' => $this->resource->status,
            'post_image' => $this->resource->post_image,
            'created_at' => $this->resource->created_at,
        ];
    }
}
