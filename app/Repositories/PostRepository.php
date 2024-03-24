<?php

namespace App\Repositories;

use App\Contracts\IPostRepository;
use App\DTO\PostDTO;
use App\Models\Post;

class PostRepository implements IPostRepository
{
    public function getAllPosts(): array
    {
        return Post::all()->toArray();
    }


    public function getPostById(int $postId): ?Post
    {
        /** @var Post|null $post */
        $post = Post::query()->find($postId);

        return $post;
    }
    public function createPost(PostDTO $postDTO): Post
    {
        $post = new Post();
        $post->title = $postDTO->getTitle();
        $post->description = $postDTO->getDescription();
        $post->user_id = $postDTO->getUserId();
        $post->save();

        return $post;
    }

    public function updatePost(PostDTO $postDTO, Post $post): Post
    {
        $post->title = $postDTO->getTitle();
        $post->description = $postDTO->getDescription();
        $post->user_id = $postDTO->getUserId();
        $post->save();

        return $post;
    }

    public function deletePost(int $id): void
    {
        $post = Post::query()->find($id);
        $post?->delete();
    }
    public function getAllPostComments(int $postId): array
    {
        $post = $this->getPostById($postId);
        if ($post === null) {
            return [];
        }

        return $post->comments->toArray();
    }
}
