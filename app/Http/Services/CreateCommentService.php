<?php

namespace App\Http\Services;

use App\Contracts\ICommentRepository;
use App\DTO\CommentDTO;
use App\Models\Comment;
use App\Repositories\CommentRepository;

class CreateCommentService
{
    private ICommentRepository $commentRepository;
    public function __construct()
    {
        $this->commentRepository= new CommentRepository();
    }

    public function execute(CommentDTO $commentDTO): Comment
    {
        return $this->commentRepository->createComment($commentDTO);
    }
}
