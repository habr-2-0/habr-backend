<?php

namespace App\Http\Services;

use App\Contracts\ICommentRepository;
use App\DTO\CommentDTO;
use App\Exceptions\ModelNotFoundException;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CommentService
{
    /**
     * @var ICommentRepository|CommentRepository
     */
    private ICommentRepository|CommentRepository $commentRepository;

    /**
     *
     */
    public function __construct()
    {
        $this->commentRepository = new CommentRepository();
    }

    /**
     * @param CommentDTO $commentDTO
     * @return Comment
     */
    public function create(CommentDTO $commentDTO): Comment
    {
        return $this->commentRepository->createComment($commentDTO);
    }

    /**
     * @param int $id
     * @return Comment
     * @throws ModelNotFoundException
     */
    public function show(int $id): Comment
    {
        $commentWithId = $this->commentRepository->getCommentById($id);

        if ($commentWithId === null) {
            throw new ModelNotFoundException(__('messages.comment_not_found'));
        }

        return $commentWithId;
    }

    /**
     * @param CommentDTO $commentDTO
     * @param int $commentId
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function update(CommentDTO $commentDTO, int $commentId): JsonResponse

    {
        $commentWithId = $this->commentRepository->getCommentById($commentId);

        if ($commentWithId === null) {
            throw new ModelNotFoundException(__('messages.comment_not_found'));
        }

        $data = $this->commentRepository->updateComment($commentDTO, $commentWithId);

        return response()->json([
            'message' => __('messages.comment_updated'),
            'data' => $data,
        ], Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return Comment|JsonResponse
     * @throws ModelNotFoundException
     */
    public function delete(int $id): Comment|JsonResponse

    {
        $commentWithId = $this->commentRepository->getCommentById($id);

        if ($commentWithId === null) {
            throw new ModelNotFoundException(__('messages.record_not_found'));
        }

        $this->commentRepository->deleteComment($commentWithId);

        return response()->json([
            'message' => __('messages.record_deleted'),
        ], Response::HTTP_OK);
    }
}
