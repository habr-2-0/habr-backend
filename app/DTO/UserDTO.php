<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(
        private string $name,
        private string $surname,
        private string $email,
        private int $password,
        private ?string $profile_image = null
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


    public static function fromArray(array $data): static
    {
        return new static(
            name: $data['name'],
            surname: $data['surname'],
            email: $data['email'],
            password: $data['password'],
            profile_image: $data['profile_image']
        );
    }
}
