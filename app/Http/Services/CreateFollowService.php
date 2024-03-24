<?php

namespace App\Http\Services;

use App\Contracts\IFollowRepository;
use App\DTO\FollowDTO;
use App\Models\Follow;
use App\Repositories\FollowRepository;

class CreateFollowService
{
    private IFollowRepository $followRepository;

    public function __construct()
    {
        $this->followRepository= new FollowRepository();
    }

    public function execute(FollowDTO $followDTO): Follow
    {
        return $this->followRepository->createFollow($followDTO);
    }
}
