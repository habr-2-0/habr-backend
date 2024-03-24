<?php

namespace App\Http\Controllers;

use App\Contracts\IPostRepository;
use App\DTO\PostDTO;
use App\Http\Requests\PostCommentsRequest;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostCommentsResource;
use App\Http\Resources\PostResource;
use App\Http\Services\CreatePostService;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    private IPostRepository $postRepository;

    public function __construct(IPostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = $this->postRepository->getAllPosts();

        return response()->json([
            'data' => $posts
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request,CreatePostService $service): PostResource
    {
        $validated = $request->validated();

        $post = $service->execute(PostDTO::fromArray($validated));

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
    public function update(PostRequest $request,  $postId): PostResource|JsonResponse
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

        if ($post === null){
            return response()->json([
                'message' => __('messages.post_not_found')
            ]);
        }

        $this->postRepository->deletePost($postId);

        return response()->json([
            'message' => __('messages.post_deleted')
        ]);
    }

    public function getAllPostComments(PostCommentsRequest $request): PostCommentsResource
    {
        $validated = $request->validated();
        $postId = $validated['post_id'];

        $comments = $this->postRepository->getAllPostComments($postId);

        return new PostCommentsResource($comments);
    }

}
