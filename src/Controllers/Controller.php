<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Security\JWTUtils;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

abstract class Controller
{
    protected $loggedInUserId = null;
    protected $isUserLoggedIn = false;

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->loggedInUserId = JWTUtils::validateUserLoggedIn();

        $this->isUserLoggedIn = true;
    }
}
