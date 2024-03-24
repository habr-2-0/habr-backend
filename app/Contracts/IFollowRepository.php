<?php

namespace App\Contracts;

use App\DTO\FollowDTO;
use App\Models\Follow;

interface IFollowRepository
{
    public function getAllFollows(): array;
    public function getFollowById(int $followId): ?Follow;
    public function createFollow(FollowDTO $followDTO): Follow;
    public function updateFollow(FollowDTO $followDTO, Follow $follow): Follow;
    public function deleteFollow(int $id): void;
}
