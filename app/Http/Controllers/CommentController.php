<?php

namespace App\Http\Controllers;

use App\Contracts\ICommentRepository;
use App\DTO\CommentDTO;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Services\CreateCommentService;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Http\JsonResponse;



class CommentController extends Controller
{
    /**
     * @var ICommentRepository
     */
    private ICommentRepository $commentRepository;

    public function __construct()
    {
        $this->commentRepository = new CommentRepository();
    }

    /**
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
        $comments = Comment::all();


        return response()->json(
            ['data' =>$comments]
        );
    }

    /**
     * Store a newly created resource in storage.С
     */
    public function store(CommentRequest $request, CreateCommentService $service): CommentResource
    {
        $validated =$request->validated();
        $comment =$service->execute(CommentDTO::fromArray($validated));
        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     * @param int $commentId
     * @return CommentResource|JsonResponse
     */
    public function show(int $commentId) : CommentResource|JsonResponse
    {
        $comment = Comment::query()->find($commentId);

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found'
            ], 404);
        }
        return response()->json($comment);
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request,  $commentId): CommentResource|JsonResponse
    {
        $comment = Comment::query()->find($commentId);

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found'
            ], 404);
        }

        $validated = $request->validated();

        $comment->update($validated);
        $comment->save();

        return response()->json([
            'message' => 'Comment successfully updated',
            'comment' => $comment
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $commentId): JsonResponse
    {
            $comment = Comment::query()->find($commentId);

            if ($comment === null){
                return response()->json([
                    'message' => 'Comment not found'
                ]);
            }
            $comment->delete();

        return response()->json([
            'message' => 'Comment deleted'
        ]);
    }
}
