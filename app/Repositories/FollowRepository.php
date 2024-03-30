<?php

namespace App\Repositories;

use App\Contracts\IFollowRepository;
use App\Models\Follow;
use App\Models\User;
use Carbon\Carbon;

class FollowRepository implements IFollowRepository
{
    public function createFollow($followerId, $followingId): Follow
    {
        $follow = new Follow();
        $follow->follower_id = $followerId;
        $follow->following_id = $followingId;
        $follow->date_followed = Carbon::now();
        $follow->save();

        return $follow;
    }

    public function deleteFollow($followerId, $followingId): void
    {
        Follow::where('follower_id', $followerId)
            ->where('following_id', $followingId)
            ->delete();
    }

    public function getFollowById($followerId, $followingId): Follow|null
    {
        return Follow::where('follower_id', $followerId)
            ->where('following_id', $followingId)
            ->first();
    }

    public function incrementFollowersCount(User $user): void
    {
        $user->followers_count++;
        $user->save();
    }

    public function decrementFollowersCount(User $user): void
    {
        $user->followers_count--;
        $user->save();
    }
    public function incrementFollowingCount(User $user): void
    {
        $user->following_count++;
        $user->save();
    }

    public function decrementFollowingCount(User $user): void
    {
        $user->following_count--;
        $user->save();
    }
}
