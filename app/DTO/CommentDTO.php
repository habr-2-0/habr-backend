<?php

namespace App\DTO;

class CommentDTO
{
    /**
     * @param int $comment_id
     * @param int $post_id
     * @param string $description
     */
    public function __construct(
        private readonly int $comment_id,
        private readonly int $post_id,
        private readonly string $description

    ) {

    }

    public function getCommentId(): int
    {
        return $this->comment_id;
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
            comment_id: $data['comment_id'],
            post_id: $data['post_id'],
            description: $data['description'],
        );
    }
}
