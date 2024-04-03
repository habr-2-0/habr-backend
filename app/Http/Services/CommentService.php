<?php

namespace App\Http\Services;

use App\Contracts\ICommentRepository;
use App\Contracts\IPostRepository;
use App\Contracts\IUserRepository;
use App\DTO\CommentDTO;
use App\Exceptions\ModelNotFoundException;
use App\Http\Resources\CommentResource;
use App\Repositories\CommentRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CommentService
{
    /**
     * @var ICommentRepository|CommentRepository
     */
    private ICommentRepository|CommentRepository $repository;
    private IPostRepository|PostRepository $post_repository;
    private IUserRepository|UserRepository $user_repository;

    public function __construct()
    {
        $this->repository = new CommentRepository();
        $this->post_repository = new PostRepository();
        $this->user_repository = new UserRepository();
    }

    /**
     * @param int $user_id
     * @param int $post_id
     * @param string $data
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function create(int $user_id, int $post_id, string $data): JsonResponse
    {
        $postWithId = $this->post_repository->getPostById($post_id);

        if ($postWithId === null) {
            throw new ModelNotFoundException(__('messages.post_not_found'));
        }


        $data = $this->repository->createComment($user_id, $post_id, $data);

        return response()->json([
            'message' => __('messages.comment_created'),
            'data' => new CommentResource($data),
        ], Response::HTTP_OK);
    }


    /**
     * @param CommentDTO $data
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function update(CommentDTO $data): JsonResponse
    {
        $postWithId = $this->post_repository->getPostById($data->getPostId());
        $commentWithId = $this->repository->getPostCommentById($data->getCommentId(), $postWithId);

        if ($postWithId === null) {
            throw new ModelNotFoundException(__('messages.post_not_found'));
        }

        if ($commentWithId === null) {
            throw new ModelNotFoundException(__('messages.comment_not_found'));
        }

        $updatedData = $this->repository->updateComment($data, $commentWithId);

        return response()->json([
            'message' => __('messages.comment_updated'),
            'data' => new CommentResource($updatedData),
        ], Response::HTTP_OK);
    }

    /**
     * @param int $user_id
     * @param int $comment_id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function delete(int $user_id, int $comment_id): JsonResponse

    {
        $userWithId = $this->user_repository->getUserById($user_id);
        $comment = $this->repository->getUserCommentById($comment_id, $userWithId);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.user_not_found'));
        }

        if ($comment === null) {
            throw new ModelNotFoundException(__('messages.record_not_found'));
        }

        $this->repository->deleteComment($comment);

        return response()->json([
            'message' => __('messages.record_deleted'),
        ], Response::HTTP_OK);
    }


    /**
     * @param int $user_id
     * @param int $post_id
     * @return Collection
     * @throws ModelNotFoundException
     */
    public function getUserPostComments(int $user_id, int $post_id): Collection
    {
        $userWithId = $this->user_repository->getUserById($user_id);
        $postWithId = $this->post_repository->getPostById($post_id);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.user_not_found'));
        }

        if ($postWithId === null) {
            throw new ModelNotFoundException(__('messages.post_not_found'));
        }

        return $this->repository->getUserPostComments($userWithId, $post_id);
    }

    /**
     * @param int $post_id
     * @return Collection
     * @throws ModelNotFoundException
     */
    public function getPostComments(int $post_id): Collection
    {
        $postWithId = $this->post_repository->getPostById($post_id);

        if ($postWithId === null) {
            throw new ModelNotFoundException(__('messages.post_not_found'));
        }

        return $this->repository->getPostComments($postWithId);
    }
}
