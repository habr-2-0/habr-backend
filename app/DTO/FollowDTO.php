<?php

namespace App\DTO;


class FollowDTO
{
    /**
     * @param int $follower_id
     * @param int $following_id
     */
    public function __construct(
        private readonly int $follower_id,
        private readonly int $following_id
    ) {}

    public function getFollowerId(): int
    {
        return $this->follower_id;
    }

    public function getFollowingId(): int
    {
        return $this->following_id;
    }

    public static function fromArray(array $data): self
    {
        return new static(
            follower_id: $data['follower_id'],
            following_id: $data['following_id']
        );
    }
}
