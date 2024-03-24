<?php

namespace App\Http\Controllers;

use App\Contracts\IPostRepository;
use App\DTO\PostDTO;
use App\Http\Requests\PostCommentsRequest;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Http\Services\PostService;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::simplePaginate(15);

        return response()->json([
            'data' => $posts
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request, PostService $service): PostResource
    {
        $validated = $request->validated();

        $post = $service->create(PostDTO::fromArray($validated));

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $postId): PostResource|JsonResponse
    {
        $post = $this->postRepository->getPostById($postId);

        if (!$post) {
            return response()->json([
                'message' => __('messages.post_not_found')
            ], 404);
        }

        return new PostResource($post);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, $postId): PostResource|JsonResponse
    {
        $post = $this->postRepository->getPostById($postId);

        if (!$post) {
            return response()->json([
                'message' => __('messages.post_not_found')
            ], 404);
        }

        $validated = $request->validated();
        $postDTO = PostDTO::fromArray($validated);

        $updatedPost = $this->postRepository->updatePost($postDTO, $post);

        return response()->json([
            'message' => __('messages.post_updated'),
            'post' => $updatedPost
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $postId): JsonResponse
    {
        $post = $this->postRepository->getPostById($postId);

        if ($post === null) {
            return response()->json([
                'message' => __('messages.post_not_found')
            ]);
        }

        $this->postRepository->deletePost($postId);

        return response()->json([
            'message' => __('messages.post_deleted')
        ]);
    }

    public function getPostComments(PostCommentsRequest $request)
    {
        $validated = $request->validated();
        $postId = $validated['post_id'];

        $comments = $this->postRepository->getAllPostComments($postId);

        return new PostCommentsResource($comments);
    }

}
