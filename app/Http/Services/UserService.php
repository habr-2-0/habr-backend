<?php

namespace App\Http\Services;

use App\Contracts\IUserRepository;
use App\DTO\UserDTO;
use App\Exceptions\DuplicateEntryException;
use App\Exceptions\ModelDeletionException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\ModelUpdationException;
use App\Http\Resources\BaseUserResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\PublicPostResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

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
     * @return Paginator
     */
    public function index(): Paginator
    {
        return $this->repository->getAllUsers();
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
     * @param int $id
     * @return UserResource
     * @throws ModelNotFoundException
     */
    public function show(int $id): UserResource
    {
        $userWithId = $this->repository->getUserWithPosts($id);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.user_not_found'));
        }

        return new UserResource($userWithId);
    }

    /**
     * @param UserDTO $userDTO
     * @param int $user_id
     * @return User
     * @throws ModelNotFoundException
     * @throws ModelUpdationException
     */
    public function update(UserDTO $userDTO, int $user_id): User
    {
        $userWithId = $this->repository->getUserById($user_id);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.user_not_found'));
        }

        $data = new BaseUserResource($this->repository->updateUser($userDTO, $userWithId));

        throw new ModelUpdationException(__('messages.user_updated'), 0, $data);
    }

    /**
     * @param int $user_id
     * @param string $path
     * @return JsonResponse
     * @throws ModelUpdationException|ModelNotFoundException
     */
    public function upload(int $user_id, string $path): JsonResponse
    {
        $userWithId = $this->repository->getUserById($user_id);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.user_not_found'));
        }

        $this->repository->uploadProfileImage($userWithId, $path);

        throw new ModelUpdationException(
            __('messages.user_image_uploaded'),
            0,
            $userWithId
        );
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

        $posts = $this->repository->getUserWithPosts($user_id)->posts;

        return PublicPostResource::collection($posts);
    }

    /**
     * @param int $user_id
     * @param int $post_id
     * @return JsonResponse|PublicPostResource
     * @throws ModelNotFoundException
     */
    public function getPostById(
        int $user_id,
        int $post_id,
    ): JsonResponse|PublicPostResource
    {
        /** @var User|null $userWithId */
        $userWithId = $this->repository->getUserById($user_id);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.user_not_found'));
        }

        $post = $this->repository->getUserPostById($user_id, $post_id);

        if ($post === null) {
            throw new ModelNotFoundException(__('messages.post_not_found'));
        }

        return new PublicPostResource($post);
    }
}
