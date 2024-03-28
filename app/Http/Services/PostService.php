<?php

namespace App\Http\Services;

use App\Contracts\IPostRepository;
use App\DTO\PostDTO;
use App\Exceptions\BusinessException;
use App\Exceptions\ModelDeletionException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\ModelUpdationException;
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
     * @return Post
     */
    public function create(PostDTO $postDTO): Post
    {
        $post = $this->repository->createPost($postDTO);

        $files = $postDTO->files;

        foreach($files as $key => $file) {

            // идет сохранение файл в storage

            $path = "some path";

            $postFile = new PostFile();
            $postFile->path = $path;
            $postFile->key = $key;
            $postFile->post_id = $post->id;

            $postFile->save();
        }

        return $post;
    }

    /**
     * @param int $id
     * @return Post
     * @throws ModelNotFoundException
     */
    public function show(int $id): Post
    {
        $postWithId = $this->repository->getPostById($id);

        if (Auth::user()->id !== $postWithId->user_id) {
            $this->repository->incrementPostViews($postWithId);
        }

        if ($postWithId === null) {
            throw new ModelNotFoundException(__('messages.post_not_found'));
        }

        return $postWithId;
    }

    /**
     * @param PostDTO $postDTO
     * @param int $id
     * @return Post
     * @throws BusinessException
     * @throws ModelNotFoundException
     * @throws ModelUpdationException
     */
    public function update(PostDTO $postDTO, int $id): Post
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
        throw new ModelUpdationException(__('messages.post_updated'), 0, $data);
    }

    /**
     * @param int $id
     * @return Post|JsonResponse
     * @throws ModelDeletionException
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
        throw new ModelDeletionException(__('messages.record_deleted'));
    }

    /**
     * @param int $post_id
     * @return JsonResponse|AnonymousResourceCollection
     * @throws ModelNotFoundException
     */
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

    /**
     * @param int $post_id
     * @param int $comment_id
     * @return JsonResponse|CommentResource
     * @throws ModelNotFoundException
     */
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























