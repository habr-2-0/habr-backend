<?php

namespace App\Http\Services;

use App\Contracts\IUserRepository;
use App\DTO\UserDTO;
use App\Exceptions\BusinessException;
use App\Http\Resources\PostResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserService
{
    private IUserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function create(UserDTO $userDTO): User
    {
        $userWithEmail = $this->repository->getUserByEmail($userDTO->getEmail());
        if ($userWithEmail !== null) {
            throw new BusinessException(__('messages.user_email_already_exists'));
        }

        return $this->repository->createUser($userDTO);
    }

    public function update(UserDTO $userDTO, int $id): User
    {
        $userWithId = $this->repository->getUserById($id);
        $userWithEmail = $this->repository->getUserByEmail($userDTO->getEmail());

        if ($userWithEmail !== null && $userWithId->email !== $userDTO->getEmail()) {
            throw new BusinessException(__('messages.user_email_already_exists'));
        } else {
            return $this->repository->updateUser($userDTO, $userWithId);
        }
    }

    public function show(int $id): User
    {
        $userWithId = $this->repository->getUserById($id);

        if ($userWithId === null) {
            throw new BusinessException(__('messages.user_not_found'));
        }

        return $userWithId;
    }

    public function delete(int $id): User|JsonResponse
    {
        $userWithId = $this->repository->getUserById($id);

        if ($userWithId === null) {
            return response()->json([
                'message' => __('messages.record_not_found')
            ], 400);
        } else {
            $this->repository->deleteUser($userWithId);
        }

        return response()->json([
            'message' => __('messages.record_deleted')
        ]);
    }

    public function getPosts(int $user_id): JsonResponse|AnonymousResourceCollection
    {
        /** @var User|null $userWithId */
        $userWithId = $this->repository->getUserById($user_id);

        if ($userWithId === null) {
            throw new BusinessException(__('messages.user_not_found'));
        }

        $posts = $userWithId->posts;

        return PostResource::collection($posts);
    }

    public function getPostById(
        int $user_id,
        int $post_id,
    ): JsonResponse|PostResource
    {
        /** @var User|null $userWithId */
        $userWithId = $this->repository->getUserById($user_id);

        if ($userWithId === null) {
            throw new BusinessException(__('messages.user_not_found'));
        }

        $posts = $userWithId->posts;

        $post = $posts->where('id', $post_id)->first();

        if ($post === null) {
            throw new BusinessException(__('messages.post_not_found'));
        }

        return new PostResource($post);
    }
}
