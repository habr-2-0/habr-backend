<?php

namespace App\Contracts;

use App\DTO\PostDTO;
use App\Models\Post;

interface IPostRepository
{
    public function getPostById(int $post_id): ?Post;
    public function createPost(PostDTO $postDTO): Post;
    public function updatePost(PostDTO $postDTO, Post $post): Post;
    public function deletePost(Post $post): void;
    public function incrementPostViews(Post $post): void;
}
