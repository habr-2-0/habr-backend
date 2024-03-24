<?php

namespace App\Contracts;

use App\DTO\PostDTO;
use App\Models\Post;

interface IPostRepository
{
    public function getAllPosts(): array;
    public function getPostById(int $postId): ?Post;
    public function createPost(PostDTO $postDTO): Post;
    public function updatePost(PostDTO $postDTO, Post $post): Post;
    public function deletePost(int $id): void;
    public function getAllPostComments(int $postId): array;
}
