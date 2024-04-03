<?php

namespace App\Http\Controllers;

use App\DTO\CommentDTO;
use App\Exceptions\BusinessException;
use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\BaseCommentResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PublicCommentResource;
use App\Http\Services\CommentService;
use App\Models\Comment;
use http\Env\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.С
     * @param CommentRequest $request
     * @param int $post_id
     * @param CommentService $service
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function store(
        FormRequest    $request,
        int            $post_id,
        CommentService $service
    ): JsonResponse
    {
        $user_id = Auth::user()->id;

        $validated = $request->validate([
            'description' => 'required|string'
        ]);

        $data = $validated['description'];

        return $service->create($user_id, $post_id, $data);
    }

    /**
     * Update the specified resource in storage.
     * @param CommentRequest $request
     * @param CommentService $service
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function update(
        CommentRequest $request,
        CommentService $service,
    ): JsonResponse
    {
        $validated = $request->validated();

        $data = CommentDTO::fromArray($validated);

        return $service->update($data);
    }


    /**
     * Remove the specified resource from storage.
     * @param int $comment_id
     * @param CommentService $service
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function destroy(
        int            $comment_id,
        CommentService $service
    ): JsonResponse
    {
        $user_id = Auth::user()->id;

        return $service->delete($user_id, $comment_id);
    }


    /**
     * @param int $post_id
     * @param CommentService $service
     * @return AnonymousResourceCollection
     * @throws ModelNotFoundException
     */
    public function getUserPostComments(
        int            $post_id,
        CommentService $service
    ): AnonymousResourceCollection
    {
        $user_id = Auth::user()->id;

        return BaseCommentResource::collection($service->getUserPostComments($user_id, $post_id));
    }

    /**
     * @param int $post_id
     * @param CommentService $service
     * @return AnonymousResourceCollection
     * @throws ModelNotFoundException
     */
    public function getPostComments(
        int            $post_id,
        CommentService $service
    ): AnonymousResourceCollection
    {
        return PublicCommentResource::collection($service->getPostComments($post_id));
    }
}
