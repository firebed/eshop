<?php

namespace Eshop\Services\SpeedEx\Exceptions;

use Exception;
use Throwable;

class SpeedExException extends Exception
{
    public mixed $response = null;
    
    public function __construct($response = null, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $this->response = $response;
        
        parent::__construct($message, $code, $previous);
    }
}