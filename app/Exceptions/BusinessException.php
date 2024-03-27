<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use PHPUnit\Event\Code\Throwable;
use Symfony\Component\HttpFoundation\Response;

class BusinessException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => $this->getMessage(),
        ], $this->getCode());
    }
}

