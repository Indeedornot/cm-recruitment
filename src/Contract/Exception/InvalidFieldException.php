<?php

namespace App\Contract\Exception;

use Exception;
use Throwable;

class InvalidFieldException extends Exception
{
    private string $field;

    public function __construct(string $field, ?Throwable $previous = null)
    {
        $this->field = $field;
        parent::__construct(sprintf('Invalid field: %s', $field), 0, $previous);
    }

    public function getField(): string
    {
        return $this->field;
    }
}
