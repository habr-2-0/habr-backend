<?php

namespace App\Http\Services;

use App\Contracts\IFollowRepository;
use App\DTO\FollowDTO;
use App\Exceptions\BusinessException;
use App\Models\Follow;
use App\Repositories\FollowRepository;
use Illuminate\Http\JsonResponse;

class CreateFollowService
{
    private IFollowRepository $followRepository;

    public function __construct()
    {
        $this->followRepository= new FollowRepository();
    }

    public function create(FollowDTO $followDTO): Follow
    {
        return $this->followRepository->createFollow($followDTO);
    }

    public function show(int $followId): Follow
    {
        $follow = $this->followRepository->getFollowById($followId);

        if ($follow === null) {
            throw new BusinessException(__('messages.follow_not_found'));
        }
        return $follow;
    }
    public function update(FollowDTO $followDTO, int $followId): Follow

    {
        $follow = $this->followRepository->getFollowById($followId);

        if ($follow === null) {
            throw new BusinessException(__('messages.follow_not_found'));
        }

        return $this->followRepository->updateFollow($followDTO, $follow);
    }

    public function delete(int $followId):Follow|JsonResponse

    {
        $follow = $this->followRepository->getFollowById($followId);

            if ($follow === null) {
                return response()->json([
                    'message' => __('messages.follow_not_found')
                ], 400);
            }else{
                $this->followRepository->deleteFollow($followId);
            }

            return response()->json([
                'message' => __('messages.follow_deleted')
            ], 200);
    }



    public function execute(FollowDTO $followDTO): Follow
    {
        return $this->followRepository->createFollow($followDTO);
    }
}
