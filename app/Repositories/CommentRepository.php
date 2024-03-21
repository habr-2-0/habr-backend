<?php

namespace App\Repositories;

use App\Contracts\ICommentRepository;
use App\DTO\CommentDTO;
use App\Models\Comment;

class CommentRepository implements ICommentRepository
{
    public function getAllComments(): array
    {
        return Comment::all()->toArray();
    }
    public function getCommentById(int $commentId): ?Comment
    {
        /** @var Comment|null $comment */
        $comment = Comment::query()->find($commentId);

        return $comment;
    }

    public function createComment(CommentDTO $commentDTO): Comment
    {
        $comment = new Comment();
        $comment->description = $commentDTO->getDescription();
        $comment->post_id = $commentDTO->getPostId();
        $comment->user_id = $commentDTO->getUserId();
        $comment->save();

        return $comment;
    }
    public function updateComment(CommentDTO $commentDTO, Comment $comment): Comment
    {
        $comment->description = $commentDTO->getDescription();
        $comment->post_id = $commentDTO->getPostId();
        $comment->user_id = $commentDTO->getUserId();
        $comment->save();

        return $comment;
    }
    public function deleteComment(int $id): void
    {
        $comment = Comment::query()->find($id);
        if ($comment !== null) {
            $comment->delete();
        }
    }


}
