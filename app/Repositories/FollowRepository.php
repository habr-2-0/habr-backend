<?php

namespace App\Repositories;

use App\Contracts\IFollowRepository;
use App\DTO\FollowDTO;
use App\Models\Follow;

class FollowRepository implements IFollowRepository
{
    public function getAllFollows(): array
    {
        return Follow::all()->toArray();
    }

    public function getFollowById(int $followId): ?Follow
    {
        /** @var Follow|null $follow */
        $follow = Follow::query()->find($followId);

        return $follow;
    }

    public function createFollow(FollowDTO $followDTO): Follow
    {
        $follow = new Follow();
        $follow->follower_id = $followDTO->getFollowerId();
        $follow->following_id = $followDTO->getFollowingId();
        $follow->save();

        return $follow;
    }

    public function updateFollow(FollowDTO $followDTO, Follow $follow): Follow
    {
        $follow->follower_id = $followDTO->getFollowerId();
        $follow->following_id = $followDTO->getFollowingId();
        $follow->save();

        return $follow;
    }

    public function deleteFollow(int $id): void
    {
        $follow = Follow::query()->find($id);
        $follow?->delete();
    }
}
