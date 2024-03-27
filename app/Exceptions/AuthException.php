<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class AuthException extends Exception
{
    protected string $status;

    protected array|null $data;

    public function __construct(
        string $message = "",
        int    $code = 0,
        string $status = '',
               $data = null,
    )
    {
        parent::__construct($message, $code);
        $this->status = $status;
        $this->data = $data ?? null;
    }

    public function render(): JsonResponse
    {
        $response = response()->json([
            'status' => $this->status,
            'message' => $this->getMessage(),
        ], $this->getCode());


        if ($this->data !== null) {
            $content = $response->getOriginalContent();

            $content['data'] = $this->data;

            $response->setData($content);
        }


        return $response;
    }
}
