<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class PostCommentsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'comments' => $this->resource,
        ];
    }
}
