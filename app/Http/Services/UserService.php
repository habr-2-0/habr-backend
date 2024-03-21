<?php

namespace App\Http\Services;

use App\Contracts\IUserRepository;
use App\DTO\UserDTO;
use App\Exceptions\BusinessException;
use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    private IUserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function create(UserDTO $userDTO): User
    {
        $userWithEmail = $this->repository->getUserByEmail($userDTO->getEmail());
        if ($userWithEmail !== null) {
            throw new BusinessException(__('messages.user_email_already_exists'));
        }

        return $this->repository->createUser($userDTO);
    }

    public function update(UserDTO $userDTO, User $user): User
    {
        $userWithEmail = $this->repository->getUserByEmail($userDTO->getEmail());

        if ($userWithEmail !== null) {
            throw new BusinessException(__('messages.user_email_already_exists'));
        } else if ($user->email !== $userDTO->getEmail()) {
            return $this->repository->updateUser($userDTO, $user);
        }
    }
}
