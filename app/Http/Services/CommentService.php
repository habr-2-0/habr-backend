<?php

namespace App\Http\Services;

use App\Contracts\ICommentRepository;
use App\DTO\CommentDTO;
use App\Exceptions\ModelDeletionException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\ModelUpdationException;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Http\JsonResponse;

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
     * @return Comment
     * @throws ModelNotFoundException|ModelUpdationException
     */
    public function update(CommentDTO $commentDTO, int $commentId): Comment

    {
        $commentWithId = $this->commentRepository->getCommentById($commentId);

        if ($commentWithId === null) {
            throw new ModelNotFoundException(__('messages.comment_not_found'));
        }

        $data = $this->commentRepository->updateComment($commentDTO, $commentWithId);
        throw new ModelUpdationException(__('messages.comment_updated'), 0, $data);
    }

    /**
     * @param int $id
     * @return Comment|JsonResponse
     * @throws ModelNotFoundException|ModelDeletionException
     */
    public function delete(int $id): Comment|JsonResponse

    {
        $commentWithId = $this->commentRepository->getCommentById($id);

        if ($commentWithId === null) {
            throw new ModelNotFoundException(__('messages.record_not_found'));
        }

        $this->commentRepository->deleteComment($commentWithId);
        throw new ModelDeletionException(__('messages.record_deleted'));
    }
}
