<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Exceptions\ModelDeletionException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\ModelUpdationException;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\PublicPostResource;
use App\Http\Resources\PublicUserResource;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index(
        UserService $service
    ): AnonymousResourceCollection
    {
        $users = $service->index();

        return PublicUserResource::collection($users);
    }


    /**
     * Display the specified resource.
     * @param int $id
     * @param UserService $service
     * @return PublicUserResource|JsonResponse
     * @throws ModelNotFoundException
     */

    public function show(
        int         $id,
        UserService $service
    ): PublicUserResource|JsonResponse
    {
        $user = $service->show($id);

        return new PublicUserResource($user);
    }

    /**
     * Update the specified resource in storage.
     * @param UserUpdateRequest $request
     * @param UserService $service
     * @return JsonResponse
     * @throws ModelUpdationException|ModelNotFoundException
     */
    public function update(
        UserUpdateRequest $request,
        UserService       $service,
    ): JsonResponse
    {
        $validated = $request->validated();

        $user = Auth::user();

        $service->update(UserDTO::fromArray($validated), $user->id);
    }


    /**
     * Remove the specified resource from storage.
     * @param UserService $service
     * @param int $id
     * @return JsonResponse
     * @throws ModelDeletionException
     * @throws ModelNotFoundException
     */
    public function destroy(
        UserService $service,
        int         $id
    ): JsonResponse
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
     * @return JsonResponse|PublicPostResource
     * @throws ModelNotFoundException
     */
    public function getUserPostById(
        UserService $service,
        int         $user_id,
        int         $post_id,
    ): JsonResponse|PublicPostResource
    {
        return $service->getPostById($user_id, $post_id);
    }
}
