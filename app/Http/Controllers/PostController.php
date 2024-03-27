<?php

namespace App\Http\Controllers;

use App\Contracts\IPostRepository;
use App\DTO\PostDTO;
use App\Exceptions\BusinessException;
use App\Exceptions\ModelDeletionException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\ModelUpdationException;
use App\Http\Requests\PostCommentsRequest;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Http\Services\PostService;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::where('status', 'published')->simplePaginate(15);

        return response()->json([
            'data' => $posts
        ]);
    }


    /**
     * Store a newly created resource in storage.
     * @param PostRequest $request
     * @param PostService $service
     * @return PostResource
     */
    public function store(
        PostRequest $request,
        PostService $service
    ): PostResource
    {
        $validated = $request->validated();

        $post = $service->create(PostDTO::fromArray($validated));

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @param PostService $service
     * @return PostResource|JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(
        int         $id,
        PostService $service
    ): PostResource|JsonResponse
    {
        $post = $service->show($id);

        return new PostResource($post);
    }


    /**
     * Update the specified resource in storage.
     * @param PostRequest $request
     * @param PostService $service
     * @param int $id
     * @return PostResource
     * @throws ModelNotFoundException
     * @throws ModelUpdationException
     * @throws BusinessException
     */
    public function update(
        PostRequest $request,
        PostService $service,
        int         $id
    ): PostResource
    {
        $validated = $request->validated();

        $service->update(PostDTO::fromArray($validated), $id);
    }

    /**
     * Remove the specified resource from storage.
     * @param PostService $service
     * @param int $id
     * @return JsonResponse|Post
     * @throws ModelNotFoundException
     * @throws ModelDeletionException|BusinessException
     */
    public function destroy(
        PostService $service,
        int         $id
    ): Post|JsonResponse
    {
        return $service->delete($id);
    }


    /**
     * @param PostService $service
     * @param int $post_id
     * @return JsonResponse|AnonymousResourceCollection
     * @throws ModelNotFoundException
     */
    public function getPostComments(
        PostService $service,
        int         $post_id
    ): JsonResponse|AnonymousResourceCollection
    {
        return $service->getComments($post_id);
    }


    /**
     * @param PostService $service
     * @param int $post_id
     * @param int $comment_id
     * @return JsonResponse|AnonymousResourceCollection
     * @throws ModelNotFoundException
     */
    public function getPostCommentById(
        PostService $service,
        int         $post_id,
        int         $comment_id,
    ): JsonResponse|PostResource
    {
        return $service->getCommentById($post_id, $comment_id);
    }
}
