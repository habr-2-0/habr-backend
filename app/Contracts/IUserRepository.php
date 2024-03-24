<?php

namespace App\Contracts;

use App\DTO\UserDTO;
use App\Models\User;

interface IUserRepository
{
    public function getUserById(int $userId): ?User;
    public function createUser(UserDTO $userDTO): ?User;
    public function updateUser(UserDTO $userDTO, User $user): ?User;
    public function getUserByEmail(string $email): ?User;
}

