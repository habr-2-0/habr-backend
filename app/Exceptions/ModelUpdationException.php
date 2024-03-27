<?php

namespace App\Exceptions;

use App\Models\Comment;
use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ModelUpdationException extends Exception
{
    protected mixed $data;

    public function __construct(string $message = "", int $code = 0, $data = null)
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'data' => $this->data,
        ], Response::HTTP_OK);
    }
}
