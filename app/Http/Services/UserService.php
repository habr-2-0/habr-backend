<?php

namespace App\Http\Services;

use App\Contracts\IUserRepository;
use App\DTO\UserDTO;
use App\Exceptions\BusinessException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

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

    public function update(UserDTO $userDTO, int $id): User
    {
        $userWithId = $this->repository->getUserById($id);
        $userWithEmail = $this->repository->getUserByEmail($userDTO->getEmail());

        if ($userWithEmail !== null && $userWithId->email !== $userDTO->getEmail()) {
            throw new BusinessException(__('messages.user_email_already_exists'));
        } else {
            return $this->repository->updateUser($userDTO, $userWithId);
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

    public function delete(int $id): User|JsonResponse
    {
        $userWithId = $this->repository->getUserById($id);

        if ($userWithId === null) {
            return response()->json([
                'message' => __('messages.record_not_found')
            ], 400);
        } else {
            $this->repository->deleteUser($userWithId);
        }

        return response()->json([
            'message' => __('messages.record_deleted')
        ]);
    }

}
