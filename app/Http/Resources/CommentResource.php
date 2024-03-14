<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Comment;
use Illuminate\Http\Request;


class CommentResource extends JsonResource
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
            'post_id' =>$this->resource->post_id,
            'description' => $this->resource->description

        ];
    }
}
