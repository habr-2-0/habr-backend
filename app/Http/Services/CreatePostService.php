<?php

namespace App\Http\Services;

use App\Contracts\IPostRepository;
use App\DTO\PostDTO;
use App\Models\Post;
use App\Repositories\PostRepository;

class CreatePostService
{
    private IPostRepository $postRepository;

    public function __construct()
    {
        $this->postRepository= new PostRepository();
    }

    public function execute(PostDTO $postDTO): Post
    {
        return $this->postRepository->createPost($postDTO);
    }
}
