<?php

namespace App\DTO;

class PostDTO
{
    /**
     * @param ?int $user_id
     * @param string $title
     * @param string $description
     * @param string $status
     * @param string $post_image
     * @param array|string $tags
     * @param int|null $views
     * @param string|null $created_at
     * @param string|null $updated_at
     */
    public function __construct(
        private readonly ?int         $user_id,
        private readonly string       $title,
        private readonly string       $description,
        private readonly string       $status,
        private readonly string       $post_image,
        private readonly array|string $tags,
        private readonly int|null     $views,
        private readonly string|null  $created_at,
        private readonly string|null  $updated_at
    )
    {
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPostImage(): string
    {
        return $this->post_image;
    }

    public function getTags(): string|array
    {
        return $this->tags;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }


    public static function fromArray(array $data): self
    {
        return new static(
            user_id: $data['user_id'] ?? null,
            title: $data['title'],
            description: $data['description'],
            status: $data['status'],
            post_image: $data['post_image'],
            tags: $data['tags'],
            views: $data['views'] ?? null,
            created_at: $data['created_at'] ?? null,
            updated_at: $data['updated_at'] ?? null,
        );
    }
}
