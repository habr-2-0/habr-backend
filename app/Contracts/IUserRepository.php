<?php

namespace App\Contracts;

use App\DTO\UserDTO;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\Paginator;

interface IUserRepository
{
    public function getAllUsers(): Paginator;

    public function getUserById(int $userId): ?User;

    public function getUserWithPosts(int $userId): ?User;

    public function getUserPostById(int $userId, int $postId): ?Post;

    public function createUser(UserDTO $userDTO): ?User;

    public function updateUser(UserDTO $userDTO, User $user): ?User;

    function uploadProfileImage(User $user, string $path): User;

    public function getUserByEmail(string $email): ?User;
}

