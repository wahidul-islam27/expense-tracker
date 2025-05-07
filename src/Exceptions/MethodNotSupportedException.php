<?php

declare(strict_types = 1);

namespace App\Exceptions;

class MethodNotSupportedException extends \Exception {
    protected $message = "method can not be not implemented";
}