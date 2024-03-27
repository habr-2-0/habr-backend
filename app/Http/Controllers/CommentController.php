<?php

namespace App\Http\Controllers;

use App\DTO\CommentDTO;
use App\Exceptions\BusinessException;
use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Services\CommentService;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class CommentController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $comments = Comment::simplePaginate(15);

        return response()->json([
            'data' => $comments
        ]);
    }

    /**
     * Store a newly created resource in storage.С
     * @param CommentRequest $request
     * @param CommentService $service
     * @return CommentResource
     */
    public function store(
        CommentRequest       $request,
        CommentService $service
    ): CommentResource
    {
        $validated = $request->validated();

        $comment = $service->create(CommentDTO::fromArray($validated));

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @param CommentService $service
     * @return CommentResource|JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(
        int                  $id,
        CommentService $service
    ): CommentResource|JsonResponse
    {
        $comment = $service->show($id);

        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     * @param CommentRequest $request
     * @param CommentService $service
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function update(
        CommentRequest $request,
        CommentService $service,
        int            $id
    ): JsonResponse
    {
        $validated = $request->validated();

        $service->update(CommentDTO::fromArray($validated), $id);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $comment_id
     * @param CommentService $service
     * @return JsonResponse
     */
    public function destroy(
        int                  $id,
        CommentService $service
    ): JsonResponse
    {
        return $service->delete($id);
    }
}
