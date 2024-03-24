<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Js;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::simplePaginate(15);

        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param RegisterRequest $request
     * @return UserResource
     */
    public function store(RegisterRequest $request, UserService $service): UserResource
    {
        $validated = $request->validated();

        $user = $service->create(UserDTO::fromArray($validated));

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return UserResource|JsonResponse
     */

    public function show(int $id, UserService $service): UserResource|JsonResponse
    {

        $user = $service->show($id);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     * @param RegisterRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(RegisterRequest $request, UserService $service, int $id): JsonResponse
    {
        $validated = $request->validated();

        $service->update(UserDTO::fromArray($validated), $id);

        return response()->json([
            'message' => __('messages.user_updated')
        ], Response::HTTP_OK);
    }


    /**
     * Remove the specified resource from storage.
     * @return JsonResponse|User
     */
    public function destroy(UserService $service, int $id): User|JsonResponse
    {
        return $service->delete($id);
    }
}
