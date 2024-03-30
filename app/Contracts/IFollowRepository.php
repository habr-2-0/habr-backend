<?php

namespace App\Contracts;

use App\Models\Follow;
use App\Models\User;

interface IFollowRepository
{
    public function getFollowById($followerId, $followingId): Follow|null;

    public function createFollow($followerId, $followingId): Follow;

    public function deleteFollow($followerId, $followingId): void;

    public function incrementFollowersCount(User $user): void;
    public function decrementFollowersCount(User $user): void;
}
