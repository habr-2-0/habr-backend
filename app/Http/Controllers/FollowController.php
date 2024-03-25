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

    public function index(): JsonResponse
    {
        $follows = Follow::simplePaginate(15);

        return response()->json([
            'data' => $follows
        ]);
    }

    public function store(FollowRequest $request, CreateFollowService $service): FollowResource
    {
        $validated = $request->validated();

        $follow = $service->create(FollowDTO::fromArray($validated));

        return new FollowResource($follow);
    }

    public function show(int $followId, CreateFollowService $service): JsonResponse|FollowResource
    {
        $follow = $service->show($followId);

        return new FollowResource($follow);
    }

    public function update(FollowRequest $request,CreateFollowService $service, int  $followId): JsonResponse
    {
        $validated = $request->validated();

        $service->update(FollowDTO::fromArray($validated), $followId);

        return response()->json([
            'message' => __('messages.follow_updated')
        ], Response::HTTP_OK);
    }

    public function destroy(int $followId, CreateFollowService $service): JsonResponse
    {
        return $service->delete($followId);
    }
}
