<?php

namespace Eshop\Services\Skroutz\Exceptions;

use Exception;

class SkroutzException extends Exception
{
    private array $errors;

    public function __construct(array $errors)
    {
        parent::__construct();
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}