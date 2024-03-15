<?php

namespace App\Contracts;

use App\DTO\CommentDTO;
use App\Models\Comment;

interface ICommentRepository
{
    public function getCommentById(int $commentId): ?Comment;
    public function createComment(CommentDTO $commentDTO): Comment;
}
