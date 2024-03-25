<?php

namespace App\Http\Services;

use App\Contracts\IPostRepository;
use App\DTO\PostDTO;
use App\Exceptions\BusinessException;
use App\Exceptions\ModelDeletionException;
use App\Exceptions\ModelNotFoundException;
use App\Http\Resources\CommentResource;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostService
{
    private IPostRepository $repository;

    public function __construct()
    {
        $this->repository = new PostRepository();
    }

    public function create(PostDTO $postDTO): Post
    {
        return $this->repository->createPost($postDTO);
    }

    public function update(PostDTO $postDTO, int $id): Post
    {
        $postWithId = $this->repository->getPostById($id);

        return $this->repository->updatePost($postDTO, $postWithId);
    }

    public function show(int $id): Post
    {
        $postWithId = $this->repository->getPostById($id);

        if ($postWithId === null) {
            throw new ModelNotFoundException(__('messages.post_not_found'));
        }

        return $postWithId;
    }

    public function delete(int $id): Post|JsonResponse
    {
        $postWithId = $this->repository->getPostById($id);

        if ($postWithId === null) {
            throw new ModelNotFoundException(__('messages.record_not_found'));
        }

        $this->repository->deletePost($postWithId);
        throw new ModelDeletionException(__('messages.record_deleted'));
    }

    public function getComments(
        int $post_id
    ): JsonResponse|AnonymousResourceCollection
    {
        /** @var Post|null $postWithId */
        $postWithId = $this->repository->getPostById($post_id);

        if ($postWithId === null) {
            throw new ModelNotFoundException(__('messages.post_not_found'));
        }

        $comments = $postWithId->comments;

        return CommentResource::collection($comments);
    }

    public function getCommentById(
        int $post_id,
        int $comment_id,
    ): JsonResponse|CommentResource
    {
        /** @var Post|null $postWithId */
        $postWithId = $this->repository->getPostById($post_id);

        if ($postWithId === null) {
            throw new ModelNotFoundException(__('messages.post_not_found'));
        }

        $comments = $postWithId->comments;

        $comment = $comments->where('id', $comment_id)->first();

        if ($comment === null) {
            throw new ModelNotFoundException(__('messages.comment_not_found'));
        }

        return new CommentResource($comment);
    }
}























