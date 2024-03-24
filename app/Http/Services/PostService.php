<?php

namespace App\Http\Services;

use App\Contracts\IPostRepository;
use App\DTO\PostDTO;
use App\Exceptions\BusinessException;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;

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
            throw new BusinessException(__('messages.post_not_found'));
        }

        return $postWithId;
    }

    public function delete(int $id): Post|JsonResponse
    {
        $postWithId = $this->repository->getPostById($id);

        if ($postWithId === null) {
            return response()->json([
                'message' => __('messages.record_not_found')
            ], 400);
        } else {
            $this->repository->deletePost($postWithId);
        }

        return response()->json([
            'message' => __('messages.record_deleted')
        ]);
    }


}























