<?php

namespace App\Repositories;

use App\Contracts\IUserRepository;
use App\DTO\UserDTO;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;

class UserRepository implements IUserRepository
{
    public function getUserById(int $userId): ?User
    {
        /** @var User|null $user */
        $user = User::query()
            ->where('id', $userId)
            ->first();

        return $user;
    }

    public function getUserWithPosts(int $userId): ?User
    {
        /** @var User|null $user */
        $user = User::query()
            ->where('id', $userId)
            ->with(['posts' => function ($query) {
                $query->where('status', 'published');
            }])
            ->first();

        return $user;
    }

    public function getUserPostById(int $userId, int $postId): ?Post
    {
        /** @var Post|null $post */
        $post = Post::where('user_id', $userId)
            ->where('id', $postId)
            ->where('status', 'published')
            ->first();

        return $post;
    }

    public function getAllUsers(): Paginator
    {
        return User::simplePaginate(15);
    }

    public function createUser(UserDTO $userDTO): User
    {
        $user = new User();
        $user->name = $userDTO->getName();
        $user->surname = $userDTO->getSurname();
        $user->email = $userDTO->getEmail();
        $user->password = $userDTO->getPassword();
        $user->profile_image = $userDTO->getProfileImage();
        $user->followers_count = $userDTO->getFollowersCount();
        $user->save();

        return $user;
    }

    public function updateUser(UserDTO $userDTO, User $user): User
    {
        if ($userDTO->getName()) {
            $user->name = $userDTO->getName();
        }

        if ($userDTO->getSurname()) {
            $user->surname = $userDTO->getSurname();
        }

        if ($userDTO->getProfileImage()) {
            $user->profile_image = $userDTO->getProfileImage();
        }
        $user->save();

        return $user;
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    public function getUserByEmail(string $email): ?User
    {
        /** @var User|null $user */
        $user = User::query()->where('email', $email)->first();

        return $user;
    }
}
