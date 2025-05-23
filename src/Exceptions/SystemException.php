<?php

declare(strict_types = 1);

namespace App\Exceptions;

class SystemException extends \Exception {
    public function __construct($code, $message)
    {
        parent::__construct($message, $code);
    }
}