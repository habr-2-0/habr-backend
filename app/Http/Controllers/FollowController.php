<?php

namespace App\Http\Controllers;

use App\Contracts\IFollowRepository;
use App\DTO\FollowDTO;
use App\Http\Requests\FollowRequest;
use App\Http\Services\CreateFollowService;
use Illuminate\Http\JsonResponse;

class FollowController extends Controller
{
    private IFollowRepository $followRepository;

    public function __construct(IFollowRepository $followRepository)
    {
        $this->followRepository = $followRepository;
    }

    public function index(): JsonResponse
    {
        $follows = $this->followRepository->getAllFollows();

        return response()->json([
            'data' => $follows
        ]);
    }

    public function store(FollowRequest $request, CreateFollowService $service): JsonResponse
    {
        $validated = $request->validated();
        $followDTO = FollowDTO::fromArray($validated);

        $follow = $service->execute($followDTO);

        return response()->json([
            'message' => __('messages.follow_created'),
            'follow' => $follow
        ], 201);
    }

    public function show(int $followId): JsonResponse
    {
        $follow = $this->followRepository->getFollowById($followId);

        if (!$follow) {
            return response()->json([
                'message' => __('messages.follow_not_found')
            ], 404);
        }

        return response()->json([
            'data' => $follow
        ]);
    }

    public function update(FollowRequest $request,  $followId): JsonResponse
    {
        $follow = $this->followRepository->getFollowById($followId);

        if (!$follow) {
            return response()->json([
                'message' => __('messages.follow_not_found')
            ], 404);
        }

        $validated = $request->validated();
        $followDTO = FollowDTO::fromArray($validated);

        $updatedFollow = $this->followRepository->updateFollow($followDTO, $follow);

        return response()->json([
            'message' => __('messages.follow_updated'),
            'follow' => $updatedFollow
        ]);
    }

    public function destroy(int $followId): JsonResponse
    {
        $follow = $this->followRepository->getFollowById($followId);

        if ($follow === null){
            return response()->json([
                'message' => __('messages.follow_not_found')
            ]);
        }

        $this->followRepository->deleteFollow($followId);

        return response()->json([
            'message' => __('messages.follow_deleted')
        ]);
    }
}
