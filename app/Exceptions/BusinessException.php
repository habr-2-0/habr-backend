<?php

namespace App\Exceptions;

use Exception;
use PHPUnit\Event\Code\Throwable;

class BusinessException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
