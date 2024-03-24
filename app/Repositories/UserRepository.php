<?php

namespace App\Repositories;

use App\Contracts\IUserRepository;
use App\DTO\UserDTO;
use App\Models\User;

class UserRepository implements IUserRepository
{
    public function getUserById(int $userId): ?User
    {
        /** @var User|null $user */
        $user = User::query()->find($userId);

        return $user;
    }

    public function createUser(UserDTO $userDTO): User
    {
        $user = new User();
        $user->name = $userDTO->getName();
        $user->surname = $userDTO->getSurname();
        $user->email = $userDTO->getEmail();
        $user->password = $userDTO->getPassword();
        $user->profile_image = $userDTO->getProfileImage();
        $user->save();

        return $user;
    }

    public function updateUser(UserDTO $userDTO, User $user): User
    {
        $user->name = $userDTO->getName();
        $user->surname = $userDTO->getSurname();
        $user->email = $userDTO->getEmail();
        $user->password = $userDTO->getPassword();
        $user->profile_image = $userDTO->getProfileImage();
        $user->followers_count = $userDTO->getFollowersCount();
        $user->save();

        return $user;
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        return $user;
    }

    public function getUserByEmail(string $email): ?User
    {
        /** @var User|null $user */
        $user = User::query()->where('email', $email)->first();

        return $user;
    }
}
