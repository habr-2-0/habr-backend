<?php

namespace App\Http\Controllers;

use App\DTO\CommentDTO;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Services\CreateCommentService;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class CommentController extends Controller
{


    /**
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
       $comments =Comment::simplePaginate(15);


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

        $comment =$service->create(CommentDTO::fromArray($validated));

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     * @param int $commentId
     * @return CommentResource|JsonResponse
     */
    public function show(int $commentId,CreateCommentService $service) : CommentResource|JsonResponse
    {
        $comment = $service->show($commentId);

        return new CommentResource($comment);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request,CreateCommentService $service , $commentId): CommentResource|JsonResponse
    {
        $validated = $request->validated();

        $service->update(CommentDTO::fromArray($validated), $commentId);

        return response()->json([
            'message' => __('messages.comment_updated')
        ], Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $commentId, CreateCommentService $service): JsonResponse
    {
        return $service->delete($commentId);
    }
}
