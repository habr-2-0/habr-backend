<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(
        private readonly ?string $name,
        private readonly ?string $surname,
        private readonly ?string $email,
        private readonly ?string $password,
        private readonly int $followers_count = 0,
        private readonly int $following_count = 0
    )
    {

    }

    public function getName(): string|null
    {
        return $this->name;
    }

    public function getSurname(): string|null
    {
        return $this->surname;
    }

    public function getEmail(): string|null
    {
        return $this->email;
    }


    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFollowersCount(): int
    {
        return $this->followers_count;
    }

    public function getFollowingCount(): int
    {
        return $this->following_count;
    }



    public static function fromArray(array $data): static
    {
        return new static(
            name: $data['name'] ?? null,
            surname: $data['surname'] ?? null,
            email: $data['email'] ?? null,
            password: $data['password'] ?? null,
            followers_count: $data['followers_count'] ?? 0,
            following_count: $data['following_count'] ?? 0
        );
    }
}
