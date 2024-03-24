<?php

namespace App\Repositories;

use App\Contracts\IPostRepository;
use App\DTO\PostDTO;
use App\Models\Post;

class PostRepository implements IPostRepository
{
    public function getPostById(int $post_id): ?Post
    {
        /** @var Post|null $post */

        $post = Post::query()->find($post_id);

        return $post;
    }

<<<<<<< HEAD
=======

    public function getPostById(int $postId): ?Post
    {
        /** @var Post|null $post */
        $post = Post::query()->find($postId);

        return $post;
    }
>>>>>>> ff1e41eb30edf7aadf09cefd71631d9b377fa973
    public function createPost(PostDTO $postDTO): Post
    {
        $post = new Post();
        $post->user_id = $postDTO->getUserId();
        $post->title = $postDTO->getTitle();
        $post->description = $postDTO->getDescription();
        $post->status = $postDTO->getStatus();
        $post->post_image = $postDTO->getPostImage();
        $post->tags = $postDTO->getTags();
        $post->views = $postDTO->getViews();
        $post->created_at = $postDTO->getCreatedAt();
        $post->updated_at = $postDTO->getUpdatedAt();
        $post->save();

        return $post;
    }

    public function updatePost(PostDTO $postDTO, Post $post): Post
    {
        $post->title = $postDTO->getTitle();
        $post->description = $postDTO->getDescription();
        $post->status = $postDTO->getStatus();
        $post->post_image = $postDTO->getPostImage();
        $post->tags = $postDTO->getTags();
        $post->views = $postDTO->getViews();
        $post->created_at = $postDTO->getCreatedAt();
        $post->updated_at = $postDTO->getUpdatedAt();
        $post->save();

        return $post;
    }

    public function deletePost(Post $post): Post
    {
<<<<<<< HEAD
        $post->delete();

        return $post;
=======
        $post = Post::query()->find($id);
        $post?->delete();
>>>>>>> ff1e41eb30edf7aadf09cefd71631d9b377fa973
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
