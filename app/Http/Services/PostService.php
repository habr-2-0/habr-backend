<?php

namespace App\Http\Services;

use App\Contracts\IPostRepository;
use App\DTO\PostDTO;
use App\Exceptions\BusinessException;
use App\Exceptions\ModelNotFoundException;
use App\Http\Resources\CommentResource;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PostService
{
    /**
     * @var IPostRepository|PostRepository
     */
    private IPostRepository|PostRepository $repository;

    /**
     *
     */
    public function __construct()
    {
        $this->repository = new PostRepository();
    }

    /**
     * @param PostDTO $postDTO
     * @return JsonResponse
     */
    public function create(PostDTO $postDTO): JsonResponse
    {
        $data = $this->repository->createPost($postDTO);

        return response()->json([
            'message' => __('messages.comment_created'),
            'data' => new CommentResource($data),
        ], Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return Post
     * @throws ModelNotFoundException
     */
    public function show(int $id): Post
    {
        $postWithId = $this->repository->getPostById($id);

        if ($postWithId === null) {
            throw new ModelNotFoundException(__('messages.post_not_found'));
        }

        if (Auth::user()->id !== $postWithId->user_id) {
            $this->repository->incrementPostViews($postWithId);
        }

        return $postWithId;
    }

    /**
     * @param PostDTO $postDTO
     * @param int $id
     * @return JsonResponse
     * @throws BusinessException
     * @throws ModelNotFoundException
     */
    public function update(PostDTO $postDTO, int $id): JsonResponse
    {
        $postWithId = $this->repository->getPostById($id);

        if ($postWithId === null) {
            throw new ModelNotFoundException(__('messages.post_not_found'));
        }

        if (Auth::user()->id !== $postWithId->user_id) {
            throw new BusinessException(
                __('messages.unauthorized_post_edit'),
                Response::HTTP_FORBIDDEN
            );
        }

        $data = $this->repository->updatePost($postDTO, $postWithId);

        return response()->json([
            'message' => __('messages.post_updated'),
            'data' => $data,
        ], Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return Post|JsonResponse
     * @throws ModelNotFoundException|BusinessException
     */
    public function delete(int $id): Post|JsonResponse
    {
        $postWithId = $this->repository->getPostById($id);

        if ($postWithId === null) {
            throw new ModelNotFoundException(__('messages.record_not_found'));
        }

        if (Auth::user()->id !== $postWithId->user_id) {
            throw new BusinessException(
                __('messages.unauthorized_post_delete'),
                Response::HTTP_FORBIDDEN
            );
        }

        $this->repository->deletePost($postWithId);
        return response()->json([
            'message' => __('messages.record_deleted'),
        ], Response::HTTP_OK);
    }
}























