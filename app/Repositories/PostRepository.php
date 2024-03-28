<?php

namespace App\Repositories;

use App\Contracts\IPostRepository;
use App\DTO\PostDTO;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PostRepository implements IPostRepository
{
    public function getPostById(int $post_id): ?Post
    {
        /** @var Post|null $post */

        $post = Post::query()
            ->with('post_files')
            ->find($post_id);

        return $post;
    }

    public function createPost(PostDTO $postDTO): Post
    {
        $post = new Post();
        $post->user_id = Auth::user()->id;
        $post->title = $postDTO->getTitle();
        $post->description = $postDTO->getDescription();
        $post->status = $postDTO->getStatus();// замени
        $post->post_image = $postDTO->getPostImage();
        $post->tags = json_encode($postDTO->getTags());
        $post->views = 0;
        $post->save();

        return $post;
    }

    public function updatePost(PostDTO $postDTO, Post $post): Post
    {

        if ($postDTO->getTitle()) {
            $post->title = $postDTO->getTitle();
        }
        if ($postDTO->getDescription()) {
            $post->description = $postDTO->getDescription();
        }
        if ($postDTO->getStatus()) {
            $post->status = $postDTO->getStatus();
        }
        if ($postDTO->getPostImage()) {
            $post->post_image = $postDTO->getPostImage();
        }
        if ($postDTO->getTags()) {
            $post->tags = $postDTO->getTags();
        }
        $post->updated_at = Carbon::now();
        $post->save();

        return $post;
    }

    public function deletePost(Post $post): void
    {
        $post->delete();
    }

    public function incrementPostViews(Post $post): void
    {
        $post->views++;
        $post->save();
    }
}
