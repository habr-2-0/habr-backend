<?php

namespace App\DTO;

class CommentDTO
{
    /**
     * @param int $user_id
     * @param int $post_id
     * @param string $description
     */
    public function __construct(
        private readonly int $user_id,
        private readonly int $post_id,
        private readonly string $description

    ) {

    }

    public function getUserId(): int
    {
        return $this->user_id;
    }
    public function getPostId(): int
    {
        return $this->post_id;
    }
    public function getDescription(): string
    {
        return $this->description;
    }

    public static function fromArray(array $data): static
    {
        return new static(
            user_id: $data['user_id'],
            post_id: $data['post_id'],
            description: $data['description'],
        );
    }
}
