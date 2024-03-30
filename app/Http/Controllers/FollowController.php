<?php

namespace App\Http\Controllers;

use App\Contracts\IFollowRepository;
use App\Exceptions\BusinessException;
use App\Exceptions\DuplicateEntryException;
use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\FollowRequest;
use App\Http\Resources\BaseUserResource;
use App\Http\Resources\FollowResource;
use App\Http\Services\FollowService;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FollowController extends Controller
{
    /**
     * @param FollowRequest $request
     * @param FollowService $service
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws DuplicateEntryException|BusinessException
     */
    public function store(FollowRequest $request, FollowService $service): JsonResponse
    {
        $user = Auth::user();

        $validated = $request->validated();

        $following_id = $validated['following_id'];

        return $service->follow($user, $following_id);
    }

    /**
     * @param FollowRequest $request
     * @param FollowService $service
     * @return JsonResponse
     * @throws ModelNotFoundException|BusinessException
     */
    public function destroy(FollowRequest $request, FollowService $service): JsonResponse
    {
        $user = Auth::user();

        $validated = $request->validated();

        $following_id = $validated['following_id'];

        return $service->unfollow($user, $following_id);
    }


    /**
     * @param int $id
     * @param FollowService $service
     * @return AnonymousResourceCollection
     * @throws ModelNotFoundException
     */
    public function getUserFollowings(int $id, FollowService $service): AnonymousResourceCollection
    {
       return $service->getFollowings($id);
    }

    /**
     * @param int $id
     * @param FollowService $service
     * @return AnonymousResourceCollection
     * @throws ModelNotFoundException
     */
    public function getUserFollowers(int $id, FollowService $service): AnonymousResourceCollection
    {
        return $service->getFollowers($id);
    }
}
