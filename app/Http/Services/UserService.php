<?php

namespace App\Http\Services;

use App\Contracts\IUserRepository;
use App\DTO\UserDTO;
use App\Exceptions\BusinessException;
use App\Exceptions\DuplicateEntryException;
use App\Exceptions\ModelDeletionException;
use App\Exceptions\ModelNotFoundException;
use App\Http\Resources\PostResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserService
{
    /**
     * @var IUserRepository
     */
    private IUserRepository $repository;

    /**
     *
     */
    public function __construct()
    {
        $this->repository = new UserRepository();
    }


    /**
     * @param UserDTO $userDTO
     * @return User
     * @throws DuplicateEntryException
     */
    public function create(UserDTO $userDTO): User
    {
        $userWithEmail = $this->repository->getUserByEmail($userDTO->getEmail());
        if ($userWithEmail !== null) {
            throw new DuplicateEntryException(__('messages.user_email_already_exists'));
        }

        return $this->repository->createUser($userDTO);
    }

    /**
     * @param UserDTO $userDTO
     * @param int $id
     * @return User
     * @throws DuplicateEntryException
     */
    public function update(UserDTO $userDTO, int $id): User
    {
        $userWithId = $this->repository->getUserById($id);
        $userWithEmail = $this->repository->getUserByEmail($userDTO->getEmail());

        if ($userWithEmail !== null && $userWithId->email !== $userDTO->getEmail()) {
            throw new DuplicateEntryException(__('messages.user_email_already_exists'));
        } else {
            return $this->repository->updateUser($userDTO, $userWithId);
        }
    }

    /**
     * @param int $id
     * @return User
     * @throws ModelNotFoundException
     */
    public function show(int $id): User
    {
        $userWithId = $this->repository->getUserById($id);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.user_not_found'));
        }

        return $userWithId;
    }

    /**
     * @param int $id
     * @return User|JsonResponse
     * @throws ModelDeletionException
     * @throws ModelNotFoundException
     */
    public function delete(int $id): User|JsonResponse
    {
        $userWithId = $this->repository->getUserById($id);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.record_not_found'));
        }

        $this->repository->deleteUser($userWithId);
        throw new ModelDeletionException(__('messages.record_deleted'));
    }

    /**
     * @param int $user_id
     * @return JsonResponse|AnonymousResourceCollection
     * @throws ModelNotFoundException
     */
    public function getPosts(int $user_id): JsonResponse|AnonymousResourceCollection
    {
        /** @var User|null $userWithId */
        $userWithId = $this->repository->getUserById($user_id);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.user_not_found'));
        }

        $posts = $userWithId->posts;

        return PostResource::collection($posts);
    }

    /**
     * @param int $user_id
     * @param int $post_id
     * @return JsonResponse|PostResource
     * @throws ModelNotFoundException
     */
    public function getPostById(
        int $user_id,
        int $post_id,
    ): JsonResponse|PostResource
    {
        /** @var User|null $userWithId */
        $userWithId = $this->repository->getUserById($user_id);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.user_not_found'));
        }

        $posts = $userWithId->posts;

        $post = $posts->where('id', $post_id)->first();

        if ($post === null) {
            throw new ModelNotFoundException(__('messages.post_not_found'));
        }

        return new PostResource($post);
    }
}
