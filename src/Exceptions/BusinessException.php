<?php

declare(strict_types = 1);

namespace App\Exceptions;

class BusinessException extends \Exception {
    public function __construct($code, $message)
    {
        parent::__construct($message, $code);
    }
}