<?php

namespace App\Http\Controllers;

use App\Contracts\IFollowRepository;
use App\DTO\FollowDTO;
use App\Http\Requests\FollowRequest;
use App\Http\Resources\FollowResource;
use App\Http\Services\CreateFollowService;
use App\Models\Follow;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FollowController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $follows = Follow::simplePaginate(15);

        return response()->json([
            'data' => $follows
        ]);
    }

    /**
     * @param FollowRequest $request
     * @param CreateFollowService $service
     * @return FollowResource
     */
    public function store(FollowRequest $request, CreateFollowService $service): FollowResource
    {
        $validated = $request->validated();

        $follow = $service->follow(FollowDTO::fromArray($validated));

        return new FollowResource($follow);
    }

    /**
     * @param int $followId
     * @param CreateFollowService $service
     * @return JsonResponse|FollowResource
     * @throws \App\Exceptions\BusinessException
     */
    public function show(int $followId, CreateFollowService $service): JsonResponse|FollowResource
    {
        $follow = $service->show($followId);

        return new FollowResource($follow);
    }

    /**
     * @param int $followId
     * @param CreateFollowService $service
     * @return JsonResponse
     */
    public function destroy(int $followId, CreateFollowService $service): JsonResponse
    {
        return $service->delete($followId);
    }
}
