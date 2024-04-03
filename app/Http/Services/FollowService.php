<?php

namespace App\Http\Services;

use App\Contracts\IFollowRepository;
use App\Contracts\IUserRepository;
use App\Exceptions\BusinessException;
use App\Exceptions\DuplicateEntryException;
use App\Exceptions\ModelNotFoundException;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Resources\BaseUserResource;
use App\Repositories\FollowRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class FollowService
{
    /**
     * @var FollowRepository|IFollowRepository
     * @var UserRepository|IUserRepository
     */
    private IFollowRepository|FollowRepository $follow_repository;
    private IUserRepository|UserRepository $user_repository;

    /**
     *
     */
    public function __construct()
    {
        $this->follow_repository = new FollowRepository();
        $this->user_repository = new UserRepository();
    }


    /**
     * @param Authenticatable $user
     * @param int $following_id
     * @return JsonResponse
     * @throws DuplicateEntryException
     * @throws ModelNotFoundException|BusinessException
     */
    public function follow(Authenticatable $user, int $following_id): JsonResponse
    {
        if ($user->id === $following_id) {
            throw new BusinessException(
                __('messages.follow_yourself'),
                400
            );
        }

        $userWithId = $this->user_repository->getUserById($following_id);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.user_not_found'));
        }


        if ($this->follow_repository->getFollowById($user->id, $following_id) !== null) {
            throw new DuplicateEntryException(__('messages.following_already_exists'));
        } else {
            $this->follow_repository->createFollow($user->id, $following_id);
            $this->follow_repository->incrementFollowersCount($userWithId);
            $this->follow_repository->incrementFollowingCount($user);
        }

        return response()->json([
            'message' => __('messages.followed_successfully')
        ]);
    }

    /**
     * @param Authenticatable $user
     * @param int $following_id
     * @return JsonResponse
     * @throws BusinessException
     * @throws ModelNotFoundException
     */
    public function unfollow(Authenticatable $user, int $following_id): JsonResponse
    {
        if ($user->id === $following_id) {
            throw new BusinessException(
                __('messages.unfollow_yourself'),
                400
            );
        }

        $userWithId = $this->user_repository->getUserById($following_id);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.user_not_found'));
        }

        if ($this->follow_repository->getFollowById($user->id, $following_id) === null) {
            throw new BusinessException(
                __('messages.not_followed_before'),
                Response::HTTP_BAD_REQUEST
            );
        } else {
            $this->follow_repository->deleteFollow($user->id, $following_id);
            $this->follow_repository->decrementFollowersCount($userWithId);
            $this->follow_repository->decrementFollowingCount($user);
        }

        return response()->json([
            'message' => __('messages.unfollowed_successfully')
        ]);
    }

    /**
     * @param int $user_id
     * @return AnonymousResourceCollection
     * @throws ModelNotFoundException
     */
    public function getFollowings(int $user_id): AnonymousResourceCollection
    {
        $userWithId = $this->user_repository->getUserById($user_id);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.user_not_found'));
        }

        return BaseUserResource::collection($userWithId->following);
    }

    /**
     * @param int $user_id
     * @return AnonymousResourceCollection
     * @throws ModelNotFoundException
     */
    public function getFollowers(int $user_id): AnonymousResourceCollection
    {
        $userWithId = $this->user_repository->getUserById($user_id);

        if ($userWithId === null) {
            throw new ModelNotFoundException(__('messages.user_not_found'));
        }

        return BaseUserResource::collection($userWithId->follower);
    }
}
