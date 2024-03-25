<?php

namespace App\Http\Services;

use App\Contracts\ICommentRepository;
use App\DTO\CommentDTO;
use App\Exceptions\BusinessException;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Http\JsonResponse;

class CreateCommentService
{
    private ICommentRepository $commentRepository;

    public function __construct()
    {
        $this->commentRepository= new CommentRepository();
    }

    public function create(CommentDTO $commentDTO): Comment
    {
        return $this->commentRepository->createComment($commentDTO);
    }

    public function show(int $commentId): Comment
    {
        $comment = $this->commentRepository->getCommentById($commentId);

        if ($comment === null) {
            throw new BusinessException(__('messages.comment_not_found'));
        }

        return $comment;
    }

    public function update(CommentDTO $commentDTO, int $commentId): Comment

    {
        $comment = $this->commentRepository->getCommentById($commentId);

        if ($comment === null) {
            throw new BusinessException(__('messages.comment_not_found'));
        }

        return $this->commentRepository->updateComment($commentDTO, $comment);
    }

    public function delete(int $commentId):Comment|JsonResponse

    {
        $comment = $this->commentRepository->getCommentById($commentId);

        if ($comment === null) {
            return response()->json([
                'message' => __('messages.comment_not_found')
            ], 400);
        }else{
            $this->commentRepository->deleteComment($commentId);
        }

        return response()->json([
            'message' => __('messages.comment_deleted')
        ]);
    }

    public function execute(CommentDTO $commentDTO): Comment
    {
        return $this->commentRepository->createComment($commentDTO);
    }
}
