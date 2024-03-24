<?php

namespace App\Exceptions;

use Exception;
use PHPUnit\Event\Code\Throwable;
use Symfony\Component\HttpFoundation\Response;

class BusinessException extends Exception
{
    private $statusCode;

    public function __construct(int $statusCode, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $this->statusCode = $statusCode;

        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
