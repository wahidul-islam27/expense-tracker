<?php

declare(strict_types=1);

namespace App\Exceptions;

class RouterNotFoundException extends \Exception
{
    protected $message = '404 Not Found';
}
