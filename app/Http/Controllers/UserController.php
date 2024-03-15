<?php

namespace App\Http\Controllers;

use App\Contacts\IUserRepository;
use App\DTO\UserDTO;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    private IUserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     * @param UserRequest $request
     * @return UserResource
     * @throws BusinessException
     */
    public function store(RegisterRequest $request, UserService $service): UserResource
    {
        $validated = $request->validated();

        $user = $service->create(UserDTO::fromArray($validated));

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     * @param User $user
     * @return UserResource
     */

    public function show(int $id): UserResource|JsonResponse
    {
        $user = $this->repository->getUserById($id);


        if ($user === null) {
            return response()->json([
                'message' => __('messages.user_not_found')
            ], 400);
        }
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param UserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(RegisterRequest $request, UserService $service, int $id): JsonResponse
    {
        $validated = $request->validated();

        $user = $this->repository->getUserById($id);

        $service->update(UserDTO::fromArray($validated), $user);

        return response()->json([
            'message' => __('messages.user_updated')
        ], Response::HTTP_OK);
    }


    /**
     * Remove the specified resource from storage.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $user = $this->repository->getUserById($id);


        if ($user === null) {
            $result = response()->json([
                'message' => __('messages.record_not_found')
            ], 400);
        } else {
            User::query()->find($id)->delete();
            $result = response()->json([
                'message' => __('messages.record_deleted')
            ]);
        }

        return $result;
    }
}
