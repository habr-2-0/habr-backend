<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(
        private string $name,
        private string $surname,
        private ?string $email,
        private string $password,
        private ?string $profile_image = null,
        private int $followers_count = 0
    )
    {

    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }


    public function getPassword(): string
    {
        return $this->password;
    }

    public function getProfileImage(): string|null
    {
        return $this->profile_image;
    }

    public function getFollowersCount(): int
    {
        return $this->followers_count;
    }



    public static function fromArray(array $data): static
    {
        return new static(
            name: $data['name'],
            surname: $data['surname'],
            email: $data['email'],
            password: $data['password'],
            profile_image: $data['profile_image'],
            followers_count: $data['followers_count']
        );
    }
}
