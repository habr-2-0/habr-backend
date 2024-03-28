<?php

namespace App\Http\Requests;

use App\Contracts\IPostRepository;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'post_files' => 'array',
            'tags' => 'required',
        ];
    }
}
