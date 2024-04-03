<?php

namespace App\Repositories;

use App\Contracts\ICommentRepository;
use App\DTO\CommentDTO;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentRepository implements ICommentRepository
{
    public function getPostCommentById(int $comment_id, Post $postWithId): ?Comment
    {
        /** @var Comment|null $comment */

        $comment = $postWithId->comments()->where('id', $comment_id)->first();

        return $comment;
    }

    public function getUserCommentById(int $comment_id, User $userWithId): ?Comment
    {
        /** @var Comment|null $comment */

        $comment = $userWithId->comments()->where('id', $comment_id)->first();

        return $comment;
    }

    public function createComment(int $user_id, int $post_id, string $data): Comment
    {
        $comment = new Comment();
        $comment->description = $data;
        $comment->post_id = $post_id;
        $comment->user_id = $user_id;
        $comment->save();

        return $comment;
    }

    public function updateComment(CommentDTO $commentDTO, Comment $comment): Comment
    {
        $comment->description = $commentDTO->getDescription();
        $comment->updated_at = Carbon::now();
        $comment->save();

        return $comment;
    }

    public function deleteComment(Comment $comment): void
    {
        $comment->delete();
    }

    public function getUserPostComments(User $user, int $post_id): Collection
    {
        return $user->comments()
            ->where('post_id', $post_id)
            ->get();
    }

    public function getPostComments(Post $post): Collection
    {
        return $post->comments()
            ->get();
    }
}

