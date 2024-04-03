<?php

namespace App\Contracts;

use App\DTO\CommentDTO;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ICommentRepository
{
    public function getPostCommentById(int $comment_id, Post $postWithId): ?Comment;

    public function getUserCommentById(int $comment_id, User $userWithId): ?Comment;

    public function createComment(int $user_id, int $post_id, string $data): Comment;

    public function updateComment(CommentDTO $commentDTO, Comment $comment): Comment;

    public function deleteComment(Comment $comment): void;

    public function getUserPostComments(User $user, int $post_id): Collection;
    public function getPostComments(Post $post): Collection;
}

