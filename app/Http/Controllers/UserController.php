<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Exceptions\BusinessException;
use App\Exceptions\DuplicateEntryException;
use App\Exceptions\ModelDeletionException;
use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
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
     * @param UserService $service
     * @return UserResource|JsonResponse
     * @throws ModelNotFoundException
     */

    public function show(int $id, UserService $service): UserResource|JsonResponse
    {

        $user = $service->show($id);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     * @param RegisterRequest $request
     * @param UserService $service
     * @param int $id
     * @return JsonResponse
     * @throws DuplicateEntryException
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
     * @param UserService $service
     * @param int $id
     * @return JsonResponse|User
     * @throws ModelDeletionException
     * @throws ModelNotFoundException
     */
    public function destroy(UserService $service, int $id): User|JsonResponse
    {
        return $service->delete($id);
    }

    /**
     * @param UserService $service
     * @param int $user_id
     * @return JsonResponse|AnonymousResourceCollection
     * @throws ModelNotFoundException
     */
    public function getUserPosts(
        UserService $service,
        int         $user_id
    ): JsonResponse|AnonymousResourceCollection
    {
        return $service->getPosts($user_id);
    }

    /**
     * @param UserService $service
     * @param int $user_id
     * @param int $post_id
     * @return JsonResponse|AnonymousResourceCollection
     * @throws ModelNotFoundException
     */
    public function getUserPostById(
        UserService $service,
        int         $user_id,
        int         $post_id,
    ): JsonResponse|PostResource
    {
        return $service->getPostById($user_id, $post_id);
    }
}
