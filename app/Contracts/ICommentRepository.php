<?php

namespace App\Contracts;

use App\DTO\CommentDTO;
use App\Models\Comment;

interface ICommentRepository
{
    public function getCommentById(int $comment_id): ?Comment;
    public function createComment(CommentDTO $commentDTO): Comment;
    public function updateComment(CommentDTO $commentDTO, Comment $comment): Comment;
    public function deleteComment(Comment $comment): void;
}
