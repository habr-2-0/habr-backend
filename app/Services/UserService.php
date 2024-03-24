<?php

namespace App\Services;

use App\Contacts\IUserRepository;
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

    public function show(int $id): User
    {
        $userWithId = $this->repository->getUserById($id);

        if ($userWithId === null) {
            throw new BusinessException(400, __('messages.user_not_found'));
        }

        return $userWithId;
    }

}
